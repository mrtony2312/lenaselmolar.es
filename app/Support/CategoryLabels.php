<?php

namespace App\Support;

/**
 * Display label for each canonical (Spanish) category slug used in
 * config/loja_products.php and in the /categoria/{slug} URLs.
 * Legacy Portuguese slugs are kept here too so any stale reference still
 * resolves to the right label (the URLs themselves 301 via config/redirects.php).
 */
class CategoryLabels
{
    protected static array $labels = [
        // canonical ES slugs
        'pellets-de-madera' => 'Pellets de madera',
        'estufas-de-pellets' => 'Estufas de pellets',
        'cocinas-de-lena' => 'Cocinas de leña',
        'calderas-de-lena' => 'Calderas de leña',
        'lena' => 'Leña',
        'madera-densificada' => 'Madera densificada',
        'a-granel' => 'A granel',
        // legacy PT slugs (fallback only)
        'pellets-de-madeira' => 'Pellets de madera',
        'pellets-de-madeira-e-pellets' => 'Pellets de madera',
        'chef-de-madeira' => 'Cocinas de leña',
        'fogao-a-lenha' => 'Cocinas de leña',
        'caldeira-de-lenha' => 'Calderas de leña',
        'madeira-de-fogo' => 'Leña',
        'lenha' => 'Leña',
        'madeira-compactada' => 'Madera densificada',
        'uncategorized' => 'Leña',
    ];

    /**
     * Canonical slug list for menus / iteration.
     */
    public static function all(): array
    {
        return [
            'lena' => 'Leña',
            'pellets-de-madera' => 'Pellets de madera',
            'estufas-de-pellets' => 'Estufas de pellets',
            'cocinas-de-lena' => 'Cocinas de leña',
            'calderas-de-lena' => 'Calderas de leña',
            'madera-densificada' => 'Madera densificada',
            'a-granel' => 'A granel',
        ];
    }

    public static function label(?string $slug): string
    {
        if (! $slug) {
            return '';
        }

        return self::$labels[$slug] ?? ucwords(str_replace('-', ' ', $slug));
    }
}
