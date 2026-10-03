<?php

namespace Tests\Feature\Merchant;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleFeedTest extends TestCase
{
    use RefreshDatabase;

    private function confirmShipping(string $price = '0.00'): void
    {
        config([
            'merchant.shipping.publish' => true,
            'merchant.shipping.coverage_confirmed' => true,
            'merchant.shipping.publish_transit' => true,
            'merchant.shipping.price' => $price,
        ]);
    }

    private function productJsonLd(string $html): ?array
    {
        preg_match_all('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/is', $html, $blocks);

        foreach ($blocks[1] as $json) {
            $data = json_decode($json, true);
            if (is_array($data) && ($data['@type'] ?? '') === 'Product') {
                return $data;
            }
        }

        return null;
    }

    public function test_feed_xml_contains_required_google_attributes_matching_pdp_json_ld(): void
    {
        // The catalog-wide reference-price gate only lets old_price through
        // once a discount's history is verified — simulate that here.
        config(['merchant.reference_prices_verified' => true]);
        $this->confirmShipping();

        $product = Product::factory()->create([
            'id' => 90010,
            'slug' => 'ardenforest-pellets-feed-90010',
            'price' => '320.00',
            'old_price' => '360.00',
            'ref' => 'AF-90010',
        ]);

        $feed = $this->get(route('feed.google-shopping'));
        $feed->assertOk();
        $feed->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = $feed->getContent();
        $this->assertStringContainsString('<g:id>lv-90010</g:id>', $xml);
        $this->assertStringContainsString('<g:price>360.00 EUR</g:price>', $xml);
        $this->assertStringContainsString('<g:sale_price>320.00 EUR</g:sale_price>', $xml);
        $this->assertStringContainsString('<g:availability>in_stock</g:availability>', $xml);
        $this->assertStringContainsString('<g:brand>Ardenforest</g:brand>', $xml);
        $this->assertStringContainsString('<g:google_product_category>625</g:google_product_category>', $xml);
        // No GTIN, no MPN (the legacy ref is not a part number) → identifier_exists=no, brand still sent.
        $this->assertStringContainsString('<g:identifier_exists>no</g:identifier_exists>', $xml);
        $this->assertStringNotContainsString('<g:mpn>', $xml);
        $this->assertStringContainsString('<g:country>ES</g:country>', $xml);
        $this->assertStringContainsString('<g:price>0.00 EUR</g:price>', $xml);
        $this->assertStringContainsString('<g:unit_pricing_measure>1050 kg</g:unit_pricing_measure>', $xml);

        $pdp = $this->get(route('product.show', ['slug' => $product->slug]));
        $pdp->assertOk();
        $pdp->assertSee('data-offer-price="320.00"', false);
        $pdp->assertSee('SKU:</strong> lv-90010', false);
        $pdp->assertSee('Envío gratuito', false);

        $ld = $this->productJsonLd($pdp->getContent());
        $this->assertNotNull($ld);
        $this->assertSame('320.00', $ld['offers']['price']);
        $this->assertSame('EUR', $ld['offers']['priceCurrency']);
        $this->assertSame('lv-90010', $ld['sku']);
        $this->assertSame('https://schema.org/InStock', $ld['offers']['availability']);
        $this->assertSame(14, $ld['offers']['hasMerchantReturnPolicy']['merchantReturnDays']);
        $this->assertSame('0.00', $ld['offers']['shippingDetails']['shippingRate']['value']);
        $this->assertSame('ES', $ld['offers']['shippingDetails']['shippingDestination']['addressCountry']);
    }

    public function test_unconfirmed_shipping_is_never_published_as_free(): void
    {
        config(['merchant.shipping.publish' => true, 'merchant.shipping.coverage_confirmed' => false]);

        $product = Product::factory()->create(['id' => 90020, 'slug' => 'unconfirmed-shipping-90020']);

        $xml = $this->get(route('feed.google-shopping'))->getContent();
        $this->assertStringContainsString('<g:id>lv-90020</g:id>', $xml);
        $this->assertStringNotContainsString('<g:shipping>', $xml);

        $pdp = $this->get(route('product.show', ['slug' => $product->slug]));
        $pdp->assertSee('coste y cobertura a confirmar', false);
        $pdp->assertDontSee('Envío gratuito', false);
        $this->assertArrayNotHasKey('shippingDetails', $this->productJsonLd($pdp->getContent())['offers']);
    }

    public function test_sale_price_is_suppressed_until_reference_prices_are_verified(): void
    {
        Product::factory()->create([
            'id' => 90013,
            'slug' => 'unverified-discount-90013',
            'price' => '320.00',
            'old_price' => '399.00',
        ]);

        $xml = $this->get(route('feed.google-shopping'))->getContent();

        $this->assertStringContainsString('<g:id>lv-90013</g:id>', $xml);
        $this->assertStringContainsString('<g:price>320.00 EUR</g:price>', $xml);
        $this->assertStringNotContainsString('<g:sale_price>', $xml);

        $this->get(route('product.show', ['slug' => 'unverified-discount-90013']))->assertDontSee('399,00');
    }

    public function test_legacy_feed_url_serves_the_same_generator(): void
    {
        Product::factory()->create(['id' => 90011, 'slug' => 'legacy-feed-90011']);

        $a = $this->get(route('feed.google-shopping'))->getContent();
        $b = $this->get(route('feed.google-merchant'))->getContent();

        $this->assertSame($a, $b);
        $this->get(route('feed.google-shopping'))
            ->assertHeader('Content-Disposition', 'inline; filename="google-shopping.xml"');
    }

    public function test_download_url_forces_attachment_with_the_same_xml(): void
    {
        Product::factory()->create(['id' => 90012, 'slug' => 'download-feed-90012']);

        $view = $this->get(route('feed.google-shopping'));
        $download = $this->get(route('feed.google-shopping.download'));

        $view->assertOk();
        $download->assertOk();
        $download->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $download->assertHeader('Content-Disposition', 'attachment; filename="google-shopping.xml"');
        $this->assertSame($view->getContent(), $download->getContent());
        $this->assertStringContainsString('<g:id>lv-90012</g:id>', $download->getContent());
    }

    public function test_inactive_and_manually_excluded_products_are_not_in_the_feed(): void
    {
        Product::factory()->create(['id' => 90030, 'slug' => 'inactive-90030', 'is_active' => false]);
        Product::factory()->create(['id' => 90031, 'slug' => 'excluded-90031', 'merchant_excluded' => true, 'merchant_exclusion_reason' => 'pendiente de foto']);
        Product::factory()->create(['id' => 90032, 'slug' => 'ok-90032']);

        $xml = $this->get(route('feed.google-shopping'))->getContent();

        $this->assertStringNotContainsString('lv-90030', $xml);
        $this->assertStringNotContainsString('lv-90031', $xml);
        $this->assertStringContainsString('lv-90032', $xml);

        // Withdrawn from sale: no product page either.
        $this->get(route('product.show', ['slug' => 'inactive-90030']))->assertNotFound();
    }

    public function test_duplicate_gtin_blocks_both_products(): void
    {
        Product::factory()->create(['id' => 90040, 'slug' => 'dup-a-90040', 'gtin' => '8437015545004', 'title' => 'Naturpellet – palé de 70 sacos de 15 kg', 'unit_measure_value' => 1050]);
        Product::factory()->create(['id' => 90041, 'slug' => 'dup-b-90041', 'gtin' => '8437015545004', 'title' => 'Naturpellet – palé de 40 sacos de 15 kg', 'price' => '225.00', 'unit_measure_value' => 600]);

        $xml = $this->get(route('feed.google-shopping'))->getContent();

        $this->assertStringNotContainsString('lv-90040', $xml);
        $this->assertStringNotContainsString('lv-90041', $xml);
    }

    public function test_implausible_quantity_price_is_blocked(): void
    {
        Product::factory()->create(['id' => 90050, 'slug' => 'palser-90050', 'title' => 'Palser pellet 15 kg', 'price' => '366.00', 'unit_measure_value' => null, 'unit_measure_unit' => null]);

        $this->assertStringNotContainsString('lv-90050', $this->get(route('feed.google-shopping'))->getContent());
    }

    public function test_model_names_are_not_sent_as_brands(): void
    {
        Product::factory()->create([
            'id' => 90060,
            'slug' => 'vega-90060',
            'category' => 'estufas-de-pellets',
            'title' => 'Estufa de pellets – modelo VEGA 10kw',
            'price' => '445.00',
            'unit_measure_value' => null,
            'unit_measure_unit' => null,
        ]);

        $xml = $this->get(route('feed.google-shopping'))->getContent();

        $this->assertStringContainsString('lv-90060', $xml);
        $this->assertStringNotContainsString('<g:brand>', $xml);
    }
}
