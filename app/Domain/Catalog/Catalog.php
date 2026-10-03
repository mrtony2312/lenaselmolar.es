<?php

namespace App\Domain\Catalog;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * Single source of truth for every product read on the site, in the cart,
 * at checkout, in JSON-LD and in the Google feed.
 *
 * - Loaded once per request (scoped binding) and refreshed whenever a
 *   Product is saved or deleted, so no consumer can hold an older copy.
 * - The legacy array in config/loja_products.php is only used when the
 *   `products` table does not exist (fresh install before migrate). An
 *   existing-but-empty table yields an empty catalog: an old import must
 *   never resurface with stale prices or titles.
 * - Reference ("old") prices are blanked here, once, for every consumer,
 *   until merchant.reference_prices_verified is set.
 */
class Catalog
{
    public const SOURCE_DATABASE = 'database';

    public const SOURCE_LEGACY_CONFIG = 'legacy-config';

    /** @var Collection<int, array<string, mixed>>|null */
    private ?Collection $items = null;

    private string $source = self::SOURCE_DATABASE;

    /**
     * Every product, active or not (validator / admin use).
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function everything(): Collection
    {
        return $this->items ??= $this->load();
    }

    /**
     * Products on sale: what the storefront lists and the cart accepts.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function all(): Collection
    {
        return $this->everything()->filter(fn (array $p) => $p['is_active'])->values();
    }

    public function find(int|string|null $id): ?array
    {
        if ($id === null || $id === '' || ! is_numeric($id)) {
            return null;
        }

        return $this->all()->firstWhere('id', (int) $id);
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->all()->firstWhere('slug', $slug);
    }

    public function source(): string
    {
        $this->everything();

        return $this->source;
    }

    public function refresh(): void
    {
        $this->items = null;
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function load(): Collection
    {
        $tableExists = false;

        try {
            $tableExists = Schema::hasTable('products');
        } catch (\Throwable) {
            // No database connection: treated like a missing table.
        }

        if ($tableExists) {
            $this->source = self::SOURCE_DATABASE;
            $rows = Product::query()->orderBy('id')->get()->map->toCatalogArray();
        } else {
            $this->source = self::SOURCE_LEGACY_CONFIG;
            $rows = collect(require config_path('loja_products.php'))->filter(fn ($e) => is_array($e));
        }

        return $rows->map(fn (array $row) => self::normalize($row))->values();
    }

    /**
     * Same array shape for both sources, with the reference-price rule applied.
     */
    public static function normalize(array $row): array
    {
        $row['id'] = (int) ($row['id'] ?? 0);
        $row['title'] = (string) ($row['title'] ?? '');
        $row['slug'] = (string) ($row['slug'] ?? '');
        $row['category'] = (string) ($row['category'] ?? '');
        $row['price'] = (string) ($row['price'] ?? '0');
        $row['images'] = array_values(array_filter((array) ($row['images'] ?? []), fn ($i) => is_string($i) && $i !== ''));
        $row['in_stock'] = (bool) ($row['in_stock'] ?? false);
        $row['is_active'] = (bool) ($row['is_active'] ?? true);
        $row['merchant_excluded'] = (bool) ($row['merchant_excluded'] ?? false);
        $row['merchant_exclusion_reason'] = $row['merchant_exclusion_reason'] ?? null;
        $row['short_description'] = (string) ($row['short_description'] ?? '');
        $row['description'] = (string) ($row['description'] ?? '');
        $row['ref'] = (string) ($row['ref'] ?? '');
        $row['gtin'] = $row['gtin'] ?? null;
        $row['mpn'] = $row['mpn'] ?? null;
        $row['brand'] = $row['brand'] ?? null;
        $row['color'] = (string) ($row['color'] ?? '');
        $row['hover_image'] = (string) ($row['hover_image'] ?? '');

        // A struck-through "old" price is only lawful (Directive 98/6/CE
        // art. 6 bis, RDL 1/2007) and Merchant-Center-compliant when it is the
        // lowest price actually charged in the previous 30 days.
        if (! config('merchant.reference_prices_verified')) {
            $row['old_price'] = '';
        }

        return $row;
    }
}
