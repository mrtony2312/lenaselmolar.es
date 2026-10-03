<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager les catégories avec toutes les vues
        View::composer('*', function ($view) {
            // Récupérer toutes les catégories uniques depuis loja_products
            $categories = [];

            if (config()->has('loja_products')) {
                $present = collect(config('loja_products'))
                    ->pluck('category')
                    ->filter()
                    ->unique()
                    ->all();

                // Canonical order + Spanish labels; only categories that have products.
                foreach (\App\Support\CategoryLabels::all() as $slug => $label) {
                    if (in_array($slug, $present, true)) {
                        $categories[$slug] = $label;
                    }
                }
            }

            $view->with('categories', $categories);
        });
    }
}
