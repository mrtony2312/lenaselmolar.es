<?php

namespace App\Http\Controllers;

class LandingController extends Controller
{
    /**
     * SEO landing pages defined in config/landing_pages.php.
     * Each renders unique copy plus a curated grid pulled from an
     * existing product category in config/loja_products.php.
     */
    public function show(string $slug)
    {
        $pages = config('landing_pages', []);

        if (! isset($pages[$slug])) {
            abort(404);
        }

        $page = $pages[$slug];

        $products = collect(config('loja_products', []))
            ->where('category', $page['source_category'])
            ->values();

        if (! empty($page['match'])) {
            $filtered = $products->filter(function ($p) use ($page) {
                $haystack = mb_strtolower(($p['title'] ?? '').' '.($p['short_description'] ?? ''));
                foreach ($page['match'] as $needle) {
                    if (str_contains($haystack, $needle)) {
                        return true;
                    }
                }

                return false;
            })->values();

            if ($filtered->isNotEmpty()) {
                $products = $filtered;
            }
        }

        return view('landing', [
            'slug' => $slug,
            'page' => $page,
            'products' => $products,
        ]);
    }
}
