<?php

namespace App\Console\Commands\Merchant;

use App\Domain\Merchant\GoogleFeedGenerator;
use App\Domain\Merchant\GoogleFeedValidator;
use App\Domain\Merchant\GoogleProductMapper;
use App\Domain\Merchant\Support\Gtin;
use App\Domain\Merchant\Support\ProductImage;
use App\Repositories\LojaProduct;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AuditGoogleFeedCommand extends Command
{
    protected $signature = 'merchant:audit-feed
        {--n=12 : Sample size}
        {--all : Audit every catalog product}
        {--googlebot : Send Googlebot User-Agent (HTML must stay identical)}';

    protected $description = '[Legacy] Quick DB/XML/PDP price check. Prefer merchant:validate and merchant:feed-test.';

    public function handle(
        GoogleProductMapper $mapper,
        GoogleFeedGenerator $generator,
        GoogleFeedValidator $validator,
    ): int {
        $xml = $generator->rss();
        $parsed = $this->parseXml($xml);

        $products = LojaProduct::query()->get();
        if (! $this->option('all')) {
            $products = $products->take((int) $this->option('n'));
        }

        $failures = 0;

        foreach ($products as $product) {
            $dto = $mapper->map($product);
            $feedId = $dto->id;
            $xmlItem = $parsed[$feedId] ?? null;
            $label = '#'.($product['id'] ?? '?').' '.$feedId;

            foreach ($validator->issues($dto) as $issue) {
                $this->error($label.' validator: '.$issue);
                $failures++;
            }

            if ($dto->eligible && $xmlItem === null) {
                $this->error($label.' present in DB but missing from XML');
                $failures++;
                continue;
            }

            if (! $dto->eligible) {
                continue;
            }

            if (($xmlItem['price'] ?? '') !== $dto->price) {
                $this->error($label.' XML g:price '.$xmlItem['price'].' != mapper '.$dto->price);
                $failures++;
            }

            if (($xmlItem['sale_price'] ?? null) !== $dto->salePrice) {
                $this->error($label.' XML g:sale_price mismatch');
                $failures++;
            }

            if (($xmlItem['availability'] ?? '') !== $dto->availability->value) {
                $this->error($label.' XML availability mismatch');
                $failures++;
            }

            if ($dto->gtin && ! Gtin::isValid($dto->gtin)) {
                $this->error($label.' invalid GTIN '.$dto->gtin);
                $failures++;
            }

            $relative = ltrim((string) parse_url($dto->imageLink, PHP_URL_PATH), '/');
            if ($relative !== '') {
                if (! is_file(public_path($relative))) {
                    $this->error($label.' image 404: '.$relative);
                    $failures++;
                } elseif (! ProductImage::meetsMinDimensions($relative)) {
                    $dim = ProductImage::dimensions($relative);
                    $this->warn($label.' image below 500px: '.($dim ? $dim[0].'x'.$dim[1] : 'unknown').' (Google enforces 2027-01-31)');
                }
            }

            $html = $this->fetchPdp($dto->link);

            if ($html === null) {
                $this->error($label.' PDP HTTP failed: '.$dto->link);
                $failures++;
                continue;
            }

            $ldPrice = $this->jsonLdPrice($html);
            $domPrice = $this->domPrice($html);
            $ldAvailability = $this->jsonLdAvailability($html);

            if ($ldPrice === null || abs($ldPrice - $dto->offerAmount) >= 0.005) {
                $this->error($label.' JSON-LD price '.($ldPrice ?? 'null').' != '.$dto->offerAmount);
                $failures++;
            }

            if ($domPrice === null || abs($domPrice - $dto->offerAmount) >= 0.005) {
                $this->error($label.' DOM price '.($domPrice ?? 'null').' != '.$dto->offerAmount);
                $failures++;
            }

            $expectedSchema = $dto->availability->schemaUrl();
            if ($ldAvailability && $ldAvailability !== $expectedSchema) {
                $this->error($label.' JSON-LD availability mismatch');
                $failures++;
            }
        }

        if ($failures > 0) {
            $this->error($failures.' issue(s) found.');

            return self::FAILURE;
        }

        $this->info('Audit OK — '.$products->count().' product(s), XML items: '.count($parsed));

        return self::SUCCESS;
    }

    /**
     * Same HTML as a browser: internal kernel request, no User-Agent branching.
     */
    private function fetchPdp(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        $request = Request::create($path, 'GET');

        if ($this->option('googlebot')) {
            $request->headers->set('User-Agent', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
        }

        try {
            $response = app()->handle($request, HttpKernelInterface::MAIN_REQUEST, false);
        } catch (\Throwable $e) {
            $this->warn($e->getMessage());

            return null;
        }

        if ($response->getStatusCode() >= 400) {
            return null;
        }

        return $response->getContent();
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    private function parseXml(string $xml): array
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        $items = [];

        foreach ($doc->getElementsByTagName('item') as $item) {
            $id = $this->g($item, 'id');
            if (! $id) {
                continue;
            }
            $items[$id] = [
                'price' => $this->g($item, 'price'),
                'sale_price' => $this->g($item, 'sale_price'),
                'availability' => $this->g($item, 'availability'),
                'gtin' => $this->g($item, 'gtin'),
            ];
        }

        return $items;
    }

    private function g(\DOMElement $item, string $local): ?string
    {
        foreach ($item->getElementsByTagNameNS('http://base.google.com/ns/1.0', $local) as $node) {
            $text = trim($node->textContent);

            return $text === '' ? null : $text;
        }

        return null;
    }

    private function jsonLdPrice(string $html): ?float
    {
        if (! preg_match_all('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/is', $html, $blocks)) {
            return null;
        }

        foreach ($blocks[1] as $json) {
            $data = json_decode($json, true);
            if (! is_array($data)) {
                continue;
            }
            if (($data['@type'] ?? '') === 'Product' && isset($data['offers']['price'])) {
                return round((float) $data['offers']['price'], 2);
            }
        }

        return null;
    }

    private function jsonLdAvailability(string $html): ?string
    {
        if (! preg_match_all('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/is', $html, $blocks)) {
            return null;
        }

        foreach ($blocks[1] as $json) {
            $data = json_decode($json, true);
            if (is_array($data) && ($data['@type'] ?? '') === 'Product') {
                return $data['offers']['availability'] ?? null;
            }
        }

        return null;
    }

    private function domPrice(string $html): ?float
    {
        if (preg_match('/data-offer-price="([0-9]+(?:\.[0-9]+)?)"/', $html, $m)) {
            return round((float) $m[1], 2);
        }

        return null;
    }
}
