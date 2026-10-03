<?php

namespace App\Domain\Catalog;

use App\Domain\Merchant\Support\BrandResolver;
use App\Domain\Merchant\Support\Gtin;
use App\Domain\Merchant\Support\TitleBuilder;

/**
 * The values a customer sees for a product, computed once so the page,
 * the cart, the checkout, the JSON-LD and the feed cannot drift apart.
 */
class ProductPresenter
{
    /**
     * Customer-facing title: the H1, the cart line, the order e-mail and
     * the feed g:title are this exact string.
     */
    public static function title(array $row): string
    {
        return TitleBuilder::build(
            (string) ($row['title'] ?? ''),
            BrandResolver::resolve($row),
            ($row['color'] ?? '') !== '' ? (string) $row['color'] : null,
        );
    }

    /**
     * Stable offer id / SKU: "lv-{id}". Never derived from title, price,
     * stock or image, so editing those cannot change it.
     */
    public static function sku(array $row): string
    {
        return (string) config('merchant.feed_id_prefix', 'lv-').(int) ($row['id'] ?? 0);
    }

    /**
     * Verified GTIN: the dedicated column first; the historical `ref` only as
     * a fallback when it is a checksum-valid manufacturer GTIN that is not
     * just the catalog id re-encoded. Never generated.
     *
     * @return array{value: string|null, source: string|null}
     */
    public static function gtin(array $row): array
    {
        $id = (string) (int) ($row['id'] ?? 0);

        $stored = trim((string) ($row['gtin'] ?? ''));
        if ($stored !== '') {
            return ['value' => Gtin::normalize($stored), 'source' => 'gtin'];
        }

        $ref = trim((string) ($row['ref'] ?? ''));
        if ($ref !== '' && ! str_contains($ref, $id) && ($normalized = Gtin::normalize($ref))) {
            return ['value' => $normalized, 'source' => 'ref'];
        }

        return ['value' => null, 'source' => null];
    }

    /**
     * Manufacturer part number: only the dedicated column. The legacy `ref`
     * values are WooCommerce SKUs or barcodes, not part numbers.
     */
    public static function mpn(array $row): ?string
    {
        $mpn = trim((string) ($row['mpn'] ?? ''));

        return $mpn !== '' ? mb_substr($mpn, 0, 70) : null;
    }
}
