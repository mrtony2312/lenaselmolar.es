<?php

namespace Tests\Unit\Merchant;

use App\Domain\Merchant\GoogleProductMapper;
use App\Domain\Merchant\Support\Availability;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleProductMapperTest extends TestCase
{
    use RefreshDatabase;

    public function test_maps_sku_price_sale_and_brand(): void
    {
        config(['merchant.reference_prices_verified' => true]);

        $product = Product::factory()->create([
            'id' => 90001,
            'title' => 'Ardenforest pellets – palé de 70 sacos de 15 kg',
            'slug' => 'ardenforest-pellets-test-90001',
            'price' => '320.00',
            'old_price' => '360.00',
            'ref' => 'AF-90001',
        ]);

        $dto = app(GoogleProductMapper::class)->map($product);

        $this->assertTrue($dto->eligible);
        $this->assertSame('lv-90001', $dto->id);
        $this->assertSame('320.00', number_format($dto->offerAmount, 2, '.', ''));
        $this->assertSame('360.00 EUR', $dto->price);
        $this->assertSame('320.00 EUR', $dto->salePrice);
        $this->assertSame(Availability::InStock, $dto->availability);
        $this->assertSame('Ardenforest', $dto->brand);
        $this->assertSame('inferred', $dto->brandSource);
        // A brand alone is not a unique identifier: identifier_exists=no is sent.
        $this->assertTrue($dto->sendIdentifierExists);
        $this->assertNull($dto->mpn);
        $this->assertSame('625', $dto->googleProductCategory);
        $this->assertSame('ES', $dto->shippingCountry);
        // Shipping not confirmed by default: no rate is published.
        $this->assertNull($dto->shippingPrice);
        $this->assertSame(14, $dto->returnDays);
        $this->assertSame('320.00', $dto->toJsonLd()['offers']['price']);
    }

    public function test_unverified_reference_price_is_blanked_even_when_mapping_a_model(): void
    {
        $product = Product::factory()->create(['id' => 90003, 'slug' => 'x-90003', 'price' => '320.00', 'old_price' => '360.00']);

        $dto = app(GoogleProductMapper::class)->map($product);

        $this->assertNull($dto->salePrice);
        $this->assertSame('320.00 EUR', $dto->price);
    }

    public function test_verified_gtin_column_wins_and_is_sent(): void
    {
        $product = Product::factory()->create(['id' => 90004, 'slug' => 'x-90004', 'gtin' => '4006381333931', 'ref' => '']);

        $dto = app(GoogleProductMapper::class)->map($product);

        $this->assertSame('4006381333931', $dto->gtin);
        $this->assertSame('gtin', $dto->gtinSource);
        $this->assertFalse($dto->sendIdentifierExists);
    }

    public function test_does_not_invent_gtin_from_internal_ref(): void
    {
        $product = Product::factory()->create([
            'id' => 90002,
            'title' => 'Leña seca 40 cm palé',
            'slug' => 'lena-seca-test-90002',
            'category' => 'lena',
            'ref' => '537490002',
            'brand' => null,
        ]);

        $dto = app(GoogleProductMapper::class)->map($product);

        $this->assertNull($dto->gtin);
        $this->assertNull($dto->mpn);
        $this->assertTrue($dto->sendIdentifierExists);
    }
}
