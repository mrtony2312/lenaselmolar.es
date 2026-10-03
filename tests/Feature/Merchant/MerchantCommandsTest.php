<?php

namespace Tests\Feature\Merchant;

use App\Domain\Catalog\Catalog;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MerchantCommandsTest extends TestCase
{
    use RefreshDatabase;

    private function productionReadyConfig(): void
    {
        config([
            'app.url' => 'https://lenaselmolar.es',
            'bank.iban' => 'ES00 0000 0000 0000 0000 0000',
            'merchant.shipping.publish' => true,
            'merchant.shipping.coverage_confirmed' => true,
            'merchant.shipping.publish_transit' => true,
            'merchant.nap.email' => 'info@example.es',
        ]);
        url()->forceRootUrl('https://lenaselmolar.es');
    }

    public function test_empty_products_table_never_falls_back_to_the_legacy_array(): void
    {
        $catalog = app(Catalog::class);
        $catalog->refresh();

        $this->assertSame(Catalog::SOURCE_DATABASE, $catalog->source());
        $this->assertCount(0, $catalog->all());
    }

    public function test_validate_fails_on_account_level_critical_issues(): void
    {
        Product::factory()->create(['id' => 92001, 'slug' => 'v-92001']);

        // Default test config: no IBAN, shipping not confirmed.
        $this->artisan('merchant:validate --summary')
            ->expectsOutputToContain('GOOGLE MERCHANT VALIDATION')
            ->assertFailed();
    }

    public function test_validate_passes_when_setup_and_product_are_complete(): void
    {
        $this->productionReadyConfig();
        Product::factory()->create(['id' => 92002, 'slug' => 'v-92002', 'gtin' => '4006381333931', 'brand' => 'Ardenforest']);

        $this->artisan('merchant:validate --summary')->assertSuccessful();
    }

    public function test_feed_test_compares_feed_page_schema_and_cart(): void
    {
        $this->productionReadyConfig();
        Product::factory()->create(['id' => 92003, 'slug' => 'v-92003', 'gtin' => '4006381333931', 'brand' => 'Ardenforest']);

        $this->artisan('merchant:feed-test')
            ->expectsOutputToContain('MERCHANT FEED TEST')
            ->expectsOutputToContain('ningún error CRITICAL')
            ->assertSuccessful();
    }

    public function test_feed_test_fails_while_shipping_is_unconfirmed(): void
    {
        $this->productionReadyConfig();
        config(['merchant.shipping.coverage_confirmed' => false]);
        Product::factory()->create(['id' => 92004, 'slug' => 'v-92004']);

        $this->artisan('merchant:feed-test')->assertFailed();
    }
}
