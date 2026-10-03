<?php

namespace App\Console\Commands\Merchant;

use App\Domain\Catalog\Catalog;
use App\Domain\Merchant\GoogleFeedGenerator;
use App\Domain\Merchant\GoogleProductMapper;
use App\Domain\Merchant\Support\Gtin;
use App\Domain\Merchant\Support\ProductImage;
use App\Domain\Merchant\Validation\ConsistencyChecker;
use App\Domain\Merchant\Validation\Issue;
use App\Domain\Merchant\Validation\Severity;
use App\Support\ShippingPolicy;
use DOMDocument;
use DOMElement;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * End-to-end feed test: render the live feed route, parse it with a real
 * XML parser, check structure/fields/duplicates/URLs/prices/images/
 * availability/language/categories/identifiers/shipping, then compare every
 * item with the database, product page, JSON-LD and cart.
 *
 * Fails (exit 1) when any CRITICAL issue exists, including account-level
 * ones reported by merchant:validate.
 */
class FeedTestCommand extends Command
{
    private const G_NS = 'http://base.google.com/ns/1.0';

    private const AVAILABILITY = ['in_stock', 'out_of_stock', 'preorder', 'backorder'];

    protected $signature = 'merchant:feed-test
        {--http : Also fetch the public feed, product pages and images over the network (production check)}
        {--no-consistency : Skip the page/JSON-LD/cart comparison}';

    protected $description = 'Generate, parse and verify the Google feed and its consistency with the site (exit 1 on CRITICAL)';

    /** @var list<Issue> */
    private array $issues = [];

    public function handle(GoogleFeedGenerator $generator, GoogleProductMapper $mapper, Catalog $catalog, ConsistencyChecker $consistency): int
    {
        // Isolated, in-memory session for page rendering and cart checks.
        config(['session.driver' => 'array']);

        $this->info('1/5  Generación del flux por la ruta real…');
        [$status, $headers, $xml] = $this->fetchFeed();

        if ($status !== 200) {
            $this->fail_(Issue::XML, 'HTTP', (string) $status, '200', 'El endpoint del flux no responde 200.');
        }
        if (! str_starts_with((string) ($headers['content-type'] ?? ''), 'application/xml')) {
            $this->fail_(Issue::XML, 'Content-Type', (string) ($headers['content-type'] ?? ''), 'application/xml; charset=UTF-8', 'Content-Type incorrecto.');
        }
        if (! str_starts_with(ltrim($xml), '<?xml')) {
            $this->fail_(Issue::XML, 'body', mb_substr($xml, 0, 60), '<?xml …', 'Contenido parásito antes de la declaración XML (warning PHP, HTML…).');
        }

        $this->info('2/5  Parseo XML y estructura…');
        $items = $this->parse($xml);

        $this->info('3/5  Campos, duplicados, URL, precios, imágenes, disponibilidad, idioma, categorías, identificadores, envío…');
        $this->checkItems($items);

        $this->info('4/5  Incidencias de cuenta y exclusiones (merchant:validate)…');
        $report = $generator->report();
        foreach ($report->accountIssues() as $issue) {
            $this->issues[] = $issue;
        }
        $excluded = $report->blocked();

        if (! $this->option('no-consistency')) {
            $this->info('5/5  Coherencia BASE ↔ FICHA ↔ SCHEMA.ORG ↔ FLUX ↔ CARRITO…');
            $bar = $this->output->createProgressBar($catalog->all()->count());

            foreach ($catalog->all() as $row) {
                $dto = $mapper->map($row);
                $inFeed = $items[$dto->id] ?? null;

                if ($inFeed === null && ! isset($excluded[$row['id']])) {
                    $this->fail_(Issue::CONSISTENCY, 'feed', '(ausente)', 'presente o excluido con motivo', 'Producto activo ausente del flux sin exclusión registrada.', $row['id'], $dto->sku, $dto->title);
                }

                foreach ($consistency->check($row, $dto, $inFeed) as $issue) {
                    $this->issues[] = $issue;
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);
        }

        if ($this->option('http')) {
            $this->httpChecks($items);
        }

        return $this->conclude(count($items), count($excluded));
    }

    /**
     * @return array{0: int, 1: array<string, string>, 2: string}
     */
    private function fetchFeed(): array
    {
        $request = Request::create(rtrim((string) config('app.url'), '/').'/feeds/google-shopping.xml', 'GET');

        if ($token = (string) config('merchant.feed_token')) {
            $request->query->set('token', $token);
        }

        $response = app()->handle($request, HttpKernelInterface::MAIN_REQUEST, false);
        $headers = [];

        foreach ($response->headers->all() as $name => $values) {
            $headers[strtolower($name)] = implode(', ', $values);
        }

        return [$response->getStatusCode(), $headers, (string) $response->getContent()];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function parse(string $xml): array
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $ok = $dom->loadXML($xml);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        if (! $ok) {
            $this->fail_(Issue::XML, 'XML', implode('; ', array_map(fn ($e) => trim($e->message).' (l.'.$e->line.')', $errors)), 'XML bien formado', 'El flux no es XML válido.');

            return [];
        }

        if ($dom->encoding && strtoupper($dom->encoding) !== 'UTF-8') {
            $this->fail_(Issue::XML, 'encoding', $dom->encoding, 'UTF-8', 'Codificación no UTF-8.');
        }

        $root = $dom->documentElement;
        if (! $root || $root->nodeName !== 'rss' || $root->getAttribute('version') !== '2.0') {
            $this->fail_(Issue::XML, 'root', $root?->nodeName ?? '(none)', '<rss version="2.0">', 'Raíz RSS 2.0 ausente.');
        }
        if ($root && $root->lookupNamespaceURI('g') !== self::G_NS) {
            $this->fail_(Issue::XML, 'xmlns:g', (string) $root?->lookupNamespaceURI('g'), self::G_NS, 'Espacio de nombres Google ausente.');
        }

        $channel = $dom->getElementsByTagName('channel')->item(0);
        $language = $channel?->getElementsByTagName('language')->item(0)?->textContent;
        $marketLanguage = config('merchant.markets.'.config('merchant.default_market', 'ES').'.language', 'es');
        if ($language !== $marketLanguage) {
            $this->fail_(Issue::LANGUAGE, 'channel language', (string) $language, $marketLanguage, 'Idioma del flux distinto del idioma del mercado.');
        }

        $items = [];

        foreach ($dom->getElementsByTagName('item') as $node) {
            $id = $this->g($node, 'id');

            if ($id === null) {
                $this->fail_(Issue::XML, 'g:id', '(vacío)', 'id', 'Artículo sin g:id.');

                continue;
            }

            if (isset($items[$id])) {
                $this->fail_(Issue::IDENTIFIER, 'g:id', $id, 'único', 'g:id duplicado en el flux.');
            }

            $items[$id] = [
                'id' => $id,
                'title' => $this->text($node, 'title'),
                'description' => $this->text($node, 'description'),
                'link' => $this->text($node, 'link'),
                'image_link' => $this->g($node, 'image_link'),
                'additional_image_link' => $this->gAll($node, 'additional_image_link'),
                'availability' => $this->g($node, 'availability'),
                'condition' => $this->g($node, 'condition'),
                'price' => $this->g($node, 'price'),
                'sale_price' => $this->g($node, 'sale_price'),
                'brand' => $this->g($node, 'brand'),
                'gtin' => $this->g($node, 'gtin'),
                'mpn' => $this->g($node, 'mpn'),
                'identifier_exists' => $this->g($node, 'identifier_exists'),
                'google_product_category' => $this->g($node, 'google_product_category'),
                'shipping' => $this->gNode($node, 'shipping'),
            ];
        }

        $this->line('     '.count($items).' artículo(s) en el flux.');

        return $items;
    }

    /**
     * @param  array<string, array<string, mixed>>  $items
     */
    private function checkItems(array $items): void
    {
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $titles = [];

        foreach ($items as $id => $item) {
            $fail = fn (string $group, string $field, string $current, string $expected, string $reason) => $this->fail_($group, $field, $current, $expected, $reason, null, $id, (string) $item['title']);

            foreach (['title', 'description', 'link', 'image_link', 'availability', 'condition', 'price'] as $required) {
                if (($item[$required] ?? '') === '' || $item[$required] === null) {
                    $fail(Issue::XML, $required, '(vacío)', 'obligatorio', 'Campo obligatorio ausente.');
                }
            }

            if (mb_strlen((string) $item['title']) > 150) {
                $fail(Issue::XML, 'title', mb_strlen((string) $item['title']).' car.', '≤ 150', 'Título demasiado largo.');
            }
            if (mb_strlen((string) $item['description']) > 5000) {
                $fail(Issue::XML, 'description', mb_strlen((string) $item['description']).' car.', '≤ 5000', 'Descripción demasiado larga.');
            }
            if (preg_match('/<[a-z][^>]*>/i', (string) $item['description'])) {
                $fail(Issue::XML, 'description', 'HTML', 'texto plano', 'Quedan etiquetas HTML en la descripción.');
            }

            $titles[mb_strtolower((string) $item['title'])][] = $id;

            foreach (['link' => $item['link'], 'image_link' => $item['image_link']] as $field => $url) {
                if (! is_string($url) || ! str_starts_with($url, 'https://')) {
                    $fail(Issue::URL, $field, (string) $url, 'https://…', 'URL no HTTPS.');
                } elseif (parse_url($url, PHP_URL_HOST) !== $appHost) {
                    $fail(Issue::URL, $field, (string) parse_url($url, PHP_URL_HOST), (string) $appHost, 'Dominio distinto del sitio.');
                } elseif ($field === 'link' && parse_url($url, PHP_URL_QUERY)) {
                    $fail(Issue::URL, $field, $url, 'sin parámetros', 'Parámetros innecesarios en la URL del producto.');
                }
            }

            foreach (array_merge([$item['image_link']], $item['additional_image_link']) as $image) {
                $path = rawurldecode(ltrim((string) parse_url((string) $image, PHP_URL_PATH), '/'));
                if ($path !== '' && ProductImage::dimensions($path) === null) {
                    $fail(Issue::IMAGE, 'image', $path, 'HTTP 200', 'La imagen del flux no existe en el servidor.');
                }
            }

            foreach (['price' => $item['price'], 'sale_price' => $item['sale_price']] as $field => $price) {
                if ($price !== null && ! preg_match('/^\d+\.\d{2} EUR$/', (string) $price)) {
                    $fail(Issue::PRICE, $field, (string) $price, '"123.45 EUR"', 'Formato o moneda incorrectos.');
                }
            }
            if ($item['sale_price'] !== null && (float) $item['sale_price'] >= (float) $item['price']) {
                $fail(Issue::PRICE, 'sale_price', (string) $item['sale_price'], '< price', 'Precio rebajado no inferior al precio normal.');
            }
            if ($item['sale_price'] !== null && ! config('merchant.reference_prices_verified')) {
                $fail(Issue::PRICE, 'sale_price', (string) $item['sale_price'], '(ausente)', 'Promoción publicada sin precio de referencia verificado.');
            }

            if (! in_array($item['availability'], self::AVAILABILITY, true)) {
                $fail(Issue::AVAILABILITY, 'availability', (string) $item['availability'], implode(' | ', self::AVAILABILITY), 'Valor no admitido.');
            }

            if ($item['gtin'] !== null && ! Gtin::isValid($item['gtin'])) {
                $fail(Issue::IDENTIFIER, 'gtin', $item['gtin'], 'GTIN válido', 'Dígito de control/longitud incorrectos.');
            }
            if ($item['identifier_exists'] === 'no' && ($item['gtin'] || $item['mpn'])) {
                $fail(Issue::IDENTIFIER, 'identifier_exists', 'no', 'ausente', 'identifier_exists=no junto con GTIN/MPN.');
            }
            if ($item['identifier_exists'] === null && ! $item['gtin'] && ! ($item['mpn'] && $item['brand'])) {
                $fail(Issue::IDENTIFIER, 'identifier_exists', '(ausente)', 'no', 'Sin GTIN ni marca+MPN y sin identifier_exists=no.');
            }

            $gpc = (string) $item['google_product_category'];
            if ($gpc !== '' && ! $this->taxonomyHas($gpc)) {
                $fail(Issue::CATEGORY, 'google_product_category', $gpc, 'ID de la taxonomía', 'Categoría inexistente.');
            }

            $this->checkShipping($item, $fail);
        }

        foreach ($titles as $title => $ids) {
            if (count($ids) > 1) {
                $this->fail_(Issue::XML, 'title', $title, 'títulos distintos por artículo', 'Varios artículos con el mismo título ('.implode(', ', $ids).').', null, null, null, Severity::Important);
            }
        }
    }

    private function checkShipping(array $item, callable $fail): void
    {
        /** @var DOMElement|null $shipping */
        $shipping = $item['shipping'];

        if (! ShippingPolicy::confirmed()) {
            if ($shipping !== null) {
                $fail(Issue::SHIPPING, 'g:shipping', 'publicado', 'ausente', 'Se publica una tarifa no confirmada.');
            }

            return;
        }

        if ($shipping === null) {
            $fail(Issue::SHIPPING, 'g:shipping', '(ausente)', 'tarifa confirmada', 'Tarifa confirmada pero no enviada.');

            return;
        }

        $price = $shipping->getElementsByTagNameNS(self::G_NS, 'price')->item(0)?->textContent;
        $expected = number_format((float) ShippingPolicy::amount(), 2, '.', '').' EUR';

        if ($price !== $expected) {
            $fail(Issue::SHIPPING, 'g:shipping/price', (string) $price, $expected, 'Coste de envío distinto del que cobra el checkout.');
        }

        $country = $shipping->getElementsByTagNameNS(self::G_NS, 'country')->item(0)?->textContent;
        if ($country !== ShippingPolicy::country()) {
            $fail(Issue::SHIPPING, 'g:shipping/country', (string) $country, ShippingPolicy::country(), 'País de envío incorrecto.');
        }
    }

    /**
     * @param  array<string, array<string, mixed>>  $items
     */
    private function httpChecks(array $items): void
    {
        $this->info('HTTP real (red)…');
        $feedUrl = rtrim((string) config('app.url'), '/').'/feeds/google-shopping.xml';

        try {
            $response = Http::timeout(30)->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'])->get($feedUrl);

            if ($response->status() !== 200) {
                $this->fail_(Issue::XML, 'HTTP '.$feedUrl, (string) $response->status(), '200', 'El flux público no responde 200.');
            }
        } catch (\Throwable $e) {
            $this->fail_(Issue::XML, 'HTTP '.$feedUrl, $e->getMessage(), '200', 'El flux público no es accesible.');
        }

        foreach ($items as $id => $item) {
            foreach (['link' => $item['link'], 'image_link' => $item['image_link']] as $field => $url) {
                try {
                    $r = Http::timeout(20)->withoutRedirecting()->withHeaders(['User-Agent' => 'Googlebot-Image/1.0'])->head((string) $url);

                    if ($r->status() !== 200) {
                        $this->fail_(Issue::URL, $field, $r->status().' '.$url, '200 sin redirección', 'URL pública no accesible directamente.', null, $id, (string) $item['title']);
                    }
                } catch (\Throwable $e) {
                    $this->fail_(Issue::URL, $field, $e->getMessage(), '200', 'URL pública no accesible.', null, $id, (string) $item['title']);
                }
            }
        }
    }

    private function conclude(int $itemCount, int $excludedCount): int
    {
        $critical = array_filter($this->issues, fn (Issue $i) => $i->severity === Severity::Critical);
        $important = array_filter($this->issues, fn (Issue $i) => $i->severity === Severity::Important);

        usort($this->issues, fn (Issue $a, Issue $b) => [$a->severity->rank(), $a->group] <=> [$b->severity->rank(), $b->group]);

        foreach ($this->issues as $issue) {
            $this->line(sprintf(
                '[%s] %s %s%s — %s: %s (esperado: %s) — %s',
                $issue->severity->value,
                $issue->group,
                $issue->productId ? '#'.$issue->productId.' ' : '',
                $issue->sku ?? '',
                $issue->field,
                $issue->current,
                $issue->expected,
                $issue->reason
            ));
        }

        $this->newLine();
        $this->line('=====================================');
        $this->line('MERCHANT FEED TEST');
        $this->line('Artículos en el flux:   '.$itemCount);
        $this->line('Productos excluidos:    '.$excludedCount.' (detalle: php artisan merchant:validate)');
        $this->line('CRITICAL:               '.count($critical));
        $this->line('IMPORTANT:              '.count($important));
        $this->line('=====================================');

        Log::channel('merchant')->info('merchant:feed-test', [
            'items' => $itemCount,
            'excluded' => $excludedCount,
            'critical' => count($critical),
            'important' => count($important),
        ]);

        if ($critical !== []) {
            $this->error('FALLO: existen '.count($critical).' errores CRITICAL.');

            return self::FAILURE;
        }

        $this->info('OK: ningún error CRITICAL. (Esto no equivale a una aprobación de Google.)');

        return self::SUCCESS;
    }

    private function fail_(string $group, string $field, string $current, string $expected, string $reason, ?int $productId = null, ?string $sku = null, ?string $name = null, Severity $severity = Severity::Critical): void
    {
        if ($productId === null && $sku !== null && preg_match('/(\d+)$/', $sku, $m)) {
            $productId = (int) $m[1];
        }

        $this->issues[] = new Issue($severity, $group, $field, $current, $expected, $reason, '', $productId, $sku, $name);
    }

    /**
     * Direct g:* child only (g:price inside g:shipping is not the item price).
     */
    private function g(DOMElement $item, string $local): ?string
    {
        foreach ($item->childNodes as $child) {
            if ($child instanceof DOMElement && $child->namespaceURI === self::G_NS && $child->localName === $local) {
                $text = trim($child->textContent);

                return $text === '' ? null : $text;
            }
        }

        return null;
    }

    private function gNode(DOMElement $item, string $local): ?DOMElement
    {
        foreach ($item->childNodes as $child) {
            if ($child instanceof DOMElement && $child->namespaceURI === self::G_NS && $child->localName === $local) {
                return $child;
            }
        }

        return null;
    }

    /**
     * @return list<string>
     */
    private function gAll(DOMElement $item, string $local): array
    {
        $out = [];

        foreach ($item->getElementsByTagNameNS(self::G_NS, $local) as $node) {
            $out[] = trim($node->textContent);
        }

        return $out;
    }

    private function text(DOMElement $item, string $name): ?string
    {
        foreach ($item->childNodes as $child) {
            if ($child instanceof DOMElement && $child->namespaceURI === null && $child->localName === $name) {
                return trim($child->textContent);
            }
        }

        return null;
    }

    private function taxonomyHas(string $id): bool
    {
        static $ids = null;

        if ($ids === null) {
            $ids = [];
            $file = (string) config('merchant.taxonomy_file');

            if (is_file($file)) {
                foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
                    if (preg_match('/^(\d+) - /', $line, $m)) {
                        $ids[$m[1]] = true;
                    }
                }
            }
        }

        return $ids === [] || isset($ids[$id]);
    }
}
