<?php

namespace App\Domain\Merchant;

use App\Domain\Catalog\Catalog;
use App\Domain\Merchant\Support\PriceFormatter;
use App\Domain\Merchant\Validation\CatalogValidator;
use App\Domain\Merchant\Validation\ValidationReport;
use App\DTO\Merchant\GoogleProductData;
use DOMDocument;
use DOMElement;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GoogleFeedGenerator
{
    private const G_NS = 'http://base.google.com/ns/1.0';

    private ?ValidationReport $report = null;

    /** @var array<int, list<string>> */
    private array $excluded = [];

    public function __construct(
        private GoogleProductMapper $mapper,
        private GoogleFeedValidator $validator,
        private CatalogValidator $catalogValidator,
        private Catalog $catalog,
    ) {}

    /**
     * Offers sent to Google: active products that pass every CRITICAL rule.
     * Nothing is dropped silently: each withheld product and its reasons are
     * kept in excluded() and written to the `merchant` log channel.
     *
     * @return list<GoogleProductData>
     */
    public function items(): array
    {
        $report = $this->report();
        $out = [];
        $this->excluded = $report->blocked();

        foreach ($this->catalog->all() as $product) {
            $dto = $this->mapper->map($product);

            if (isset($this->excluded[$product['id']])) {
                continue;
            }

            $basic = $dto->eligible ? $this->validator->issues($dto) : ['datos obligatorios incompletos (slug, título, descripción, imagen, precio o enlace)'];

            if ($basic !== []) {
                $this->excluded[$product['id']] = array_map(fn ($i) => '[SPEC] '.$i, $basic);

                continue;
            }

            $out[] = $dto;
        }

        ksort($this->excluded);
        $this->logExclusions();

        return $out;
    }

    /**
     * @return array<int, list<string>>
     */
    public function excluded(): array
    {
        return $this->excluded;
    }

    public function report(): ValidationReport
    {
        return $this->report ??= $this->catalogValidator->run();
    }

    /**
     * Logs the exclusion list whenever it changes (not on every feed hit).
     */
    private function logExclusions(): void
    {
        $fingerprint = sha1(json_encode($this->excluded));

        try {
            if (Cache::get('merchant.feed.exclusions') === $fingerprint) {
                return;
            }

            Cache::forever('merchant.feed.exclusions', $fingerprint);
        } catch (\Throwable) {
            // Cache unavailable: log anyway.
        }

        $log = Log::channel('merchant');
        $log->info('Feed exclusions changed', ['excluded' => count($this->excluded)]);

        foreach ($this->excluded as $id => $reasons) {
            $log->warning('Product excluded from Google feed', [
                'product_id' => $id,
                'sku' => $this->report()->productSku($id),
                'reasons' => $reasons,
            ]);
        }
    }

    public function rss(): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = false;

        $rss = $dom->createElement('rss');
        $rss->setAttribute('version', '2.0');
        $rss->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:g', self::G_NS);
        $dom->appendChild($rss);

        $channel = $dom->createElement('channel');
        $rss->appendChild($channel);

        $nap = config('merchant.nap');
        $channel->appendChild($this->text($dom, 'title', $nap['legal_name'] ?? config('app.name')));
        $channel->appendChild($this->text($dom, 'link', route('home')));
        $channel->appendChild($this->text($dom, 'description', 'Catálogo de productos '.($nap['legal_name'] ?? config('app.name')).' — leña y carbón.'));
        $channel->appendChild($this->text($dom, 'language', 'es'));

        foreach ($this->items() as $item) {
            $this->appendItem($dom, $channel, $item);
        }

        $xml = $dom->saveXML();

        $check = new DOMDocument();
        libxml_use_internal_errors(true);
        $valid = $check->loadXML($xml);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        if (! $valid) {
            throw new RuntimeException('Flux Google Merchant invalide: '.implode('; ', array_map(
                fn ($e) => trim($e->message),
                $errors
            )));
        }

        return $xml;
    }

    /**
     * Snapshot for audit/history only. Google fetches the live route
     * /feeds/google-shopping.xml, generated on each request from the
     * catalog: no static copy is written under public/ (Apache would serve
     * it instead of the route and Google could read an outdated price).
     */
    public function writeToDisk(): string
    {
        $xml = $this->rss();
        $storageDir = storage_path('app/feeds');

        File::ensureDirectoryExists($storageDir);

        $storagePath = $storageDir.'/google-shopping.xml';
        File::put($storagePath, $xml);

        $stale = public_path('feeds/google-shopping.xml');
        if (is_file($stale)) {
            File::delete($stale);
        }

        return $storagePath;
    }

    private function appendItem(DOMDocument $dom, DOMElement $channel, GoogleProductData $item): void
    {
        $node = $dom->createElement('item');
        $channel->appendChild($node);

        $node->appendChild($this->gText($dom, 'id', $item->id));
        $node->appendChild($this->cdata($dom, 'title', $item->title));
        $node->appendChild($this->cdata($dom, 'description', $item->description));
        $node->appendChild($this->text($dom, 'link', $item->link));
        $node->appendChild($this->gText($dom, 'image_link', $item->imageLink));

        foreach ($item->additionalImageLinks as $extra) {
            $node->appendChild($this->gText($dom, 'additional_image_link', $extra));
        }

        $node->appendChild($this->gText($dom, 'availability', $item->availability->value));
        $node->appendChild($this->gText($dom, 'condition', $item->condition));
        $node->appendChild($this->gText($dom, 'price', $item->price));

        if ($item->salePrice) {
            $node->appendChild($this->gText($dom, 'sale_price', $item->salePrice));
        }

        if ($item->brand) {
            $node->appendChild($this->gText($dom, 'brand', $item->brand));
        }

        if ($item->gtin) {
            $node->appendChild($this->gText($dom, 'gtin', $item->gtin));
        }

        if ($item->mpn) {
            $node->appendChild($this->gText($dom, 'mpn', $item->mpn));
        }

        if ($item->sendIdentifierExists) {
            $node->appendChild($this->gText($dom, 'identifier_exists', 'no'));
        }

        if ($item->itemGroupId) {
            $node->appendChild($this->gText($dom, 'item_group_id', $item->itemGroupId));
        }

        if ($item->color) {
            $node->appendChild($this->gText($dom, 'color', $item->color));
        }

        if ($item->googleProductCategory) {
            $node->appendChild($this->gText($dom, 'google_product_category', $item->googleProductCategory));
        }

        if ($item->productType) {
            $node->appendChild($this->gText($dom, 'product_type', $item->productType));
        }

        if ($item->unitPricingMeasure && $item->unitPricingBaseMeasure) {
            $node->appendChild($this->gText($dom, 'unit_pricing_measure', $item->unitPricingMeasure));
            $node->appendChild($this->gText($dom, 'unit_pricing_base_measure', $item->unitPricingBaseMeasure));
        }

        if ($item->certificationCode) {
            $cert = $dom->createElementNS(self::G_NS, 'g:certification');
            $node->appendChild($cert);
            $cert->appendChild($this->gText($dom, 'certification_authority', 'EC'));
            $cert->appendChild($this->gText($dom, 'certification_name', 'EPREL'));
            $cert->appendChild($this->gText($dom, 'certification_code', $item->certificationCode));
        }

        // Only a rate the business has confirmed is published (ShippingPolicy).
        // Otherwise no g:shipping: an invented "0.00 EUR" would be a false
        // free-delivery claim.
        if ($item->shippingConfirmed && $item->shippingPrice !== null) {
            $shipping = $dom->createElementNS(self::G_NS, 'g:shipping');
            $node->appendChild($shipping);
            $shipping->appendChild($this->gText($dom, 'country', $item->shippingCountry));
            $shipping->appendChild($this->gText($dom, 'service', $item->shippingService));
            $shipping->appendChild($this->gText($dom, 'price', PriceFormatter::google((float) $item->shippingPrice, $item->currency)));

            if ($item->shippingTimesConfirmed) {
                $shipping->appendChild($this->gText($dom, 'min_handling_time', (string) $item->minHandlingTime));
                $shipping->appendChild($this->gText($dom, 'max_handling_time', (string) $item->maxHandlingTime));
                $shipping->appendChild($this->gText($dom, 'min_transit_time', (string) $item->minTransitTime));
                $shipping->appendChild($this->gText($dom, 'max_transit_time', (string) $item->maxTransitTime));
            }
        }
    }

    private function text(DOMDocument $dom, string $name, string $value): DOMElement
    {
        $node = $dom->createElement($name);
        $node->appendChild($dom->createTextNode($value));

        return $node;
    }

    private function gText(DOMDocument $dom, string $name, string $value): DOMElement
    {
        $node = $dom->createElementNS(self::G_NS, 'g:'.$name);
        $node->appendChild($dom->createTextNode($value));

        return $node;
    }

    private function cdata(DOMDocument $dom, string $name, string $value): DOMElement
    {
        $node = $dom->createElement($name);
        $node->appendChild($dom->createCDATASection(str_replace(']]>', ']]]]><![CDATA[>', $value)));

        return $node;
    }
}
