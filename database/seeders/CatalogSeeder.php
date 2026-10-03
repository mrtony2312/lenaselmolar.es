<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Support\CategoryLabels;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    /**
     * Seeds categories + products from the legacy array in
     * config/loja_products.php. That file is kept as the canonical import
     * source / backup; the running app reads from these tables.
     */
    public function run(): void
    {
        $position = 0;
        foreach (CategoryLabels::all() as $slug => $label) {
            Category::updateOrCreate(
                ['slug' => $slug],
                ['label' => $label, 'position' => $position++]
            );
        }

        $raw = require base_path('config/loja_products.php');
        $pelletsHogar = base_path('config/pellets_hogar_products.php');
        if (is_file($pelletsHogar)) {
            $extra = require $pelletsHogar;
            if (is_array($extra)) {
                $raw = array_merge($raw, $extra);
            }
        }

        foreach ($raw as $entry) {
            if (! is_array($entry) || empty($entry['id'])) {
                continue;
            }

            $slug = $entry['slug'] ?? Str::slug($entry['title'] ?? ('producto-'.$entry['id']));

            Product::updateOrCreate(
                ['id' => (int) $entry['id']],
                [
                    'title' => $entry['title'] ?? '',
                    'slug' => $slug,
                    'category' => $entry['category'] ?? 'lena',
                    'ref' => $entry['ref'] ?? null,
                    'price' => $this->toDecimal($entry['price'] ?? 0),
                    'old_price' => isset($entry['old_price']) && $entry['old_price'] !== ''
                        ? $this->toDecimal($entry['old_price'])
                        : null,
                    'in_stock' => (bool) ($entry['in_stock'] ?? true),
                    'color' => $entry['color'] ?? null,
                    'hover_image' => $entry['hover_image'] ?? null,
                    'images' => array_values(array_filter($entry['images'] ?? [], fn ($i) => ! empty($i))),
                    'short_description' => $entry['short_description'] ?? null,
                    'description' => $entry['description'] ?? null,
                ]
            );
        }
    }

    private function toDecimal($value): string
    {
        if (is_numeric($value)) {
            return number_format((float) $value, 2, '.', '');
        }

        $clean = preg_replace('/[^\d.]/', '', str_replace(',', '', (string) $value));

        return number_format((float) $clean, 2, '.', '');
    }
}
