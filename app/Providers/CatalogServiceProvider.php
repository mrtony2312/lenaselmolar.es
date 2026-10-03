<?php

namespace App\Providers;

use App\Domain\Cart\Cart;
use App\Domain\Catalog\Catalog;
use App\Models\Product;
use Illuminate\Support\ServiceProvider;
use Throwable;

class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // One catalog per request / queued job: loaded once, never shared
        // across jobs of a long-running worker.
        $this->app->scoped(Catalog::class);
        $this->app->scoped(Cart::class);
    }

    /**
     * Legacy views and controllers still read config('loja_products'). It is
     * re-pointed at the Catalog (same array shape, same reference-price rule)
     * so those readers cannot show a different price than the product page.
     */
    public function boot(): void
    {
        $this->hydrateLegacyConfig();

        $refresh = function () {
            $this->app->make(Catalog::class)->refresh();
            $this->hydrateLegacyConfig();
        };

        Product::saved($refresh);
        Product::deleted($refresh);
    }

    private function hydrateLegacyConfig(): void
    {
        try {
            config(['loja_products' => $this->app->make(Catalog::class)->all()->all()]);
        } catch (Throwable) {
            // No database yet (install): keep the file contents.
        }
    }
}
