<?php

namespace App\Domain\Merchant\Validation;

use App\Domain\Cart\Cart;
use App\Domain\Catalog\Catalog;
use App\Domain\Merchant\Support\PriceFormatter;
use App\DTO\Merchant\GoogleProductData;
use App\Support\Money;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * DATABASE vs PRODUCT PAGE vs SCHEMA.ORG vs FEED vs CART, per product.
 *
 * The product page is rendered through the real HTTP kernel (same HTML a
 * browser or Googlebot receives) on the APP_URL host; the cart is exercised
 * through the real Cart service in an isolated array session.
 */
class ConsistencyChecker
{
    public function __construct(private Catalog $catalog) {}

    /**
     * @param  array<string, string|list<string>|null>|null  $feedItem  parsed g:* values, null if the product is not in the feed
     * @return list<Issue>
     */
    public function check(array $row, GoogleProductData $dto, ?array $feedItem): array
    {
        $issues = [];
        $add = function (Severity $s, string $field, string $current, string $expected, string $reason) use (&$issues, $dto) {
            $issues[] = new Issue($s, Issue::CONSISTENCY, $field, $current, $expected, $reason,
                'Corregir la fuente (products) o la plantilla que muestra un valor distinto; todos los canales deben leer el Catalog.',
                $dto->productId, $dto->sku, $dto->title);
        };

        $dbPrice = round(Money::toFloat($row['price']), 2);

        // DATABASE ↔ offer
        if (abs($dbPrice - $dto->offerAmount) >= 0.005) {
            $add(Severity::Critical, 'price (DB vs offer)', (string) $dto->offerAmount, (string) $dbPrice, 'El precio del flux/ficha no es el de la base de datos.');
        }

        // FEED
        if ($feedItem !== null) {
            $feedPrice = $feedItem['sale_price'] ?? $feedItem['price'];
            if ($feedPrice !== PriceFormatter::google($dbPrice, $dto->currency)) {
                $add(Severity::Critical, 'g:price', (string) $feedPrice, PriceFormatter::google($dbPrice, $dto->currency), 'Precio del flux distinto del precio de venta.');
            }
            if (($feedItem['availability'] ?? null) !== ($row['in_stock'] ? 'in_stock' : 'out_of_stock')) {
                $add(Severity::Critical, 'g:availability', (string) ($feedItem['availability'] ?? ''), $row['in_stock'] ? 'in_stock' : 'out_of_stock', 'Disponibilidad del flux distinta de la base de datos.');
            }
            foreach (['title' => $dto->title, 'link' => $dto->link, 'image_link' => $dto->imageLink, 'id' => $dto->id] as $field => $expected) {
                if (($feedItem[$field] ?? null) !== $expected) {
                    $add(Severity::Critical, 'g:'.$field, (string) ($feedItem[$field] ?? ''), $expected, 'Valor del flux distinto del de la ficha.');
                }
            }
            if (($feedItem['gtin'] ?? null) !== $dto->gtin) {
                $add(Severity::Critical, 'g:gtin', (string) ($feedItem['gtin'] ?? ''), (string) $dto->gtin, 'GTIN del flux distinto.');
            }
            if (($feedItem['brand'] ?? null) !== $dto->brand) {
                $add(Severity::Critical, 'g:brand', (string) ($feedItem['brand'] ?? ''), (string) $dto->brand, 'Marca del flux distinta.');
            }
        }

        // PRODUCT PAGE + SCHEMA.ORG
        $html = $this->render(parse_url($dto->link, PHP_URL_PATH) ?: '/', $status);

        if ($status !== 200 || $html === null) {
            $add(Severity::Critical, 'HTTP', (string) $status, '200', 'La página de destino no responde 200.');

            return $issues;
        }

        $pagePrice = preg_match('/data-offer-price="([0-9.]+)"/', $html, $m) ? round((float) $m[1], 2) : null;
        if ($pagePrice === null || abs($pagePrice - $dbPrice) >= 0.005) {
            $add(Severity::Critical, 'page price', (string) $pagePrice, (string) $dbPrice, 'El precio mostrado en la ficha no es el precio de venta.');
        }

        $pageAvailability = preg_match('/data-availability="([a-z_]+)"/', $html, $m) ? $m[1] : null;
        if ($pageAvailability !== $dto->availability->value) {
            $add(Severity::Critical, 'page availability', (string) $pageAvailability, $dto->availability->value, 'La disponibilidad mostrada no coincide.');
        }

        $h1 = preg_match('/<h1[^>]*class="lv-product__title"[^>]*>(.*?)<\/h1>/s', $html, $m) ? html_entity_decode(trim(strip_tags($m[1])), ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;
        if ($h1 !== $dto->title) {
            $add(Severity::Critical, 'page title (H1)', (string) $h1, $dto->title, 'El título de la ficha difiere del título enviado a Google.');
        }

        $canonical = preg_match('/<link rel="canonical" href="([^"]+)"/', $html, $m) ? html_entity_decode($m[1]) : null;
        if ($canonical !== $dto->link) {
            $add(Severity::Critical, 'canonical', (string) $canonical, $dto->link, 'El canonical de la ficha no es la URL enviada a Google.');
        }

        if (preg_match('/<meta name="robots" content="([^"]*noindex[^"]*)"/i', $html, $m)) {
            $add(Severity::Critical, 'meta robots', $m[1], 'index', 'La ficha bloquea la indexación.');
        }

        if (! str_contains($html, '€')) {
            $add(Severity::Important, 'page currency', '(sin €)', 'EUR', 'La moneda no se muestra en la ficha.');
        }

        $ld = $this->productJsonLd($html);
        if ($ld === null) {
            $add(Severity::Critical, 'JSON-LD Product', '(ausente)', 'Product + Offer', 'La ficha no publica datos estructurados Product.');
        } else {
            $checks = [
                'JSON-LD name' => [$ld['name'] ?? null, $dto->title],
                'JSON-LD sku' => [$ld['sku'] ?? null, $dto->sku],
                'JSON-LD url' => [$ld['url'] ?? ($ld['offers']['url'] ?? null), $dto->link],
                'JSON-LD offers.price' => [$ld['offers']['price'] ?? null, number_format($dbPrice, 2, '.', '')],
                'JSON-LD offers.priceCurrency' => [$ld['offers']['priceCurrency'] ?? null, $dto->currency],
                'JSON-LD offers.availability' => [$ld['offers']['availability'] ?? null, $dto->availability->schemaUrl()],
                'JSON-LD brand' => [$ld['brand']['name'] ?? null, $dto->brand],
                'JSON-LD gtin' => [$ld['gtin13'] ?? $ld['gtin8'] ?? $ld['gtin12'] ?? $ld['gtin14'] ?? $ld['gtin'] ?? null, $dto->gtin],
            ];

            foreach ($checks as $field => [$current, $expected]) {
                if ((string) $current !== (string) $expected) {
                    $add(Severity::Critical, $field, (string) $current, (string) $expected, 'Los datos estructurados difieren del flux/ficha.');
                }
            }

            $ldImages = (array) ($ld['image'] ?? []);
            if (($ldImages[0] ?? null) !== $dto->imageLink) {
                $add(Severity::Important, 'JSON-LD image', (string) ($ldImages[0] ?? ''), $dto->imageLink, 'Imagen principal distinta entre JSON-LD y flux.');
            }
        }

        // CART (only for products that can be bought)
        if ($row['in_stock'] && $row['is_active']) {
            $line = $this->cartLine($row['id']);

            if ($line === null) {
                $add(Severity::Critical, 'cart', '(rechazado)', 'añadido', 'El producto en stock no se puede añadir al carrito.');
            } else {
                if (abs($line['price'] - $dbPrice) >= 0.005) {
                    $add(Severity::Critical, 'cart price', (string) $line['price'], (string) $dbPrice, 'El carrito cobra un precio distinto del anunciado.');
                }
                if ($line['title'] !== $dto->title) {
                    $add(Severity::Important, 'cart title', $line['title'], $dto->title, 'El carrito muestra otro título.');
                }
                if ($line['sku'] !== $dto->sku) {
                    $add(Severity::Important, 'cart sku', $line['sku'], $dto->sku, 'SKU distinto en el carrito.');
                }
            }
        } elseif (! $row['in_stock']) {
            if ($this->cartLine($row['id']) !== null) {
                $add(Severity::Critical, 'cart', 'añadido', 'rechazado', 'Un producto agotado se puede añadir al carrito.');
            }
        }

        return $issues;
    }

    private function render(string $path, ?int &$status): ?string
    {
        $request = Request::create(rtrim((string) config('app.url'), '/').$path, 'GET');

        try {
            $response = app()->handle($request, HttpKernelInterface::MAIN_REQUEST, false);
        } catch (\Throwable) {
            $status = 500;

            return null;
        }

        $status = $response->getStatusCode();

        return $status === 200 ? (string) $response->getContent() : null;
    }

    private function productJsonLd(string $html): ?array
    {
        if (! preg_match_all('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/is', $html, $blocks)) {
            return null;
        }

        foreach ($blocks[1] as $json) {
            $data = json_decode($json, true);

            if (is_array($data) && ($data['@type'] ?? null) === 'Product') {
                return $data;
            }
        }

        return null;
    }

    /**
     * Adds one unit to an empty cart and returns the priced line. Callers run
     * with session.driver=array (merchant:feed-test sets it) so no real
     * customer session is touched.
     */
    private function cartLine(int $id): ?array
    {
        session()->forget('cart');

        try {
            return (new Cart($this->catalog))->add($id, 1);
        } catch (\Throwable) {
            return null;
        } finally {
            session()->forget('cart');
        }
    }
}
