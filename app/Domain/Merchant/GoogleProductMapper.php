<?php

namespace App\Domain\Merchant;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\ProductPresenter;
use App\Domain\Merchant\Support\Availability;
use App\Domain\Merchant\Support\BrandResolver;
use App\Domain\Merchant\Support\PriceFormatter;
use App\Domain\Merchant\Support\ProductImage;
use App\Domain\Merchant\Support\TitleBuilder;
use App\DTO\Merchant\GoogleProductData;
use App\Models\Product;
use App\Support\CategoryLabels;
use App\Support\ShippingPolicy;
use App\Support\UnitPricing;

/**
 * Maps one catalog product to the offer shown on the product page, in its
 * JSON-LD and in the Google feed. All three are rendered from this DTO.
 */
class GoogleProductMapper
{
    private const EPREL_CATEGORIES = ['estufas-de-pellets', 'calderas-de-lena', 'cocinas-de-lena'];

    public function map(Product|array $product): GoogleProductData
    {
        $row = $product instanceof Product ? Catalog::normalize($product->toCatalogArray()) : $product;

        $market = config('merchant.markets.'.config('merchant.default_market', 'ES'), []);
        $currency = (string) ($market['currency'] ?? config('merchant.currency', 'EUR'));
        $returns = config('merchant.returns');

        $id = (int) ($row['id'] ?? 0);
        $sku = ProductPresenter::sku($row);

        $offerAmount = PriceFormatter::amount($row['price'] ?? 0);
        $regularAmount = PriceFormatter::amount($row['old_price'] ?? 0);
        $onSale = $regularAmount > $offerAmount && $offerAmount > 0;

        $availability = Availability::fromStock(! empty($row['in_stock']));

        $storedBrand = trim((string) ($row['brand'] ?? ''));
        $brand = BrandResolver::resolve($row);
        $gtin = ProductPresenter::gtin($row);
        $mpn = ProductPresenter::mpn($row);

        // identifier_exists=no only when the product has no GTIN and no MPN.
        // A known brand alone is not a unique identifier, and is still sent.
        $sendIdentifierExists = $gtin['value'] === null && $mpn === null;

        $mainImage = ProductImage::main($row);
        $additional = $mainImage ? ProductImage::additional($row, $mainImage) : [];

        $title = ProductPresenter::title($row);
        $description = TitleBuilder::description(
            (string) (($row['description'] ?? '') !== '' ? $row['description'] : ($row['short_description'] ?? ''))
        );

        $slug = (string) ($row['slug'] ?? '');
        $link = $slug !== '' ? route('product.show', ['slug' => $slug]) : '';

        $category = $row['category'] ?? null;
        $googleCategory = ($row['google_product_category'] ?? null)
            ?: (config('merchant.google_product_category')[$category] ?? null);

        $unitValue = isset($row['unit_measure_value']) && $row['unit_measure_value'] !== null ? (float) $row['unit_measure_value'] : null;
        $unit = $row['unit_measure_unit'] ?? null;

        $shippingConfirmed = ShippingPolicy::confirmed();

        $eligible = $id > 0
            && $slug !== ''
            && $title !== ''
            && $description !== ''
            && $mainImage !== null
            && $offerAmount > 0
            && $link !== '';

        return new GoogleProductData(
            id: $sku,
            title: $title,
            description: $description,
            link: $link,
            imageLink: $mainImage ? ProductImage::publicUrl($mainImage) : '',
            additionalImageLinks: array_map(fn ($p) => ProductImage::publicUrl($p), $additional),
            price: PriceFormatter::google($onSale ? $regularAmount : $offerAmount, $currency),
            salePrice: $onSale ? PriceFormatter::google($offerAmount, $currency) : null,
            offerAmount: $offerAmount,
            currency: $currency,
            availability: $availability,
            condition: 'new',
            brand: $brand,
            gtin: $gtin['value'],
            mpn: $mpn,
            identifierExists: ! $sendIdentifierExists,
            sendIdentifierExists: $sendIdentifierExists,
            itemGroupId: null,
            color: ($row['color'] ?? '') !== '' ? (string) $row['color'] : null,
            googleProductCategory: $googleCategory ? (string) $googleCategory : null,
            productType: $category ? CategoryLabels::label($category) : null,
            unitPricingMeasure: UnitPricing::measureString($unitValue, $unit),
            unitPricingBaseMeasure: UnitPricing::baseMeasureString($unit),
            certificationCode: $this->eprelCode($row),
            shippingCountry: ShippingPolicy::country(),
            shippingService: (string) config('merchant.shipping.service', 'Estándar'),
            shippingPrice: $shippingConfirmed ? number_format((float) ShippingPolicy::amount(), 2, '.', '') : null,
            minHandlingTime: ShippingPolicy::handlingMin(),
            maxHandlingTime: ShippingPolicy::handlingMax(),
            minTransitTime: ShippingPolicy::transitMin(),
            maxTransitTime: ShippingPolicy::transitMax(),
            returnDays: (int) ($returns['days'] ?? 14),
            eligible: $eligible,
            sku: $sku,
            inStock: $availability === Availability::InStock,
            shippingConfirmed: $shippingConfirmed,
            shippingTimesConfirmed: ShippingPolicy::timesPublished(),
            brandSource: $brand === null ? null : ($storedBrand !== '' ? 'stored' : 'inferred'),
            gtinSource: $gtin['source'],
            productId: $id,
            language: (string) ($market['language'] ?? 'es'),
        );
    }

    private function eprelCode(array $row): ?string
    {
        if (! in_array($row['category'] ?? null, self::EPREL_CATEGORIES, true)) {
            return null;
        }

        $code = trim((string) ($row['eprel_code'] ?? ''));

        return ($code !== '' && preg_match('/^\d+$/', $code)) ? $code : null;
    }
}
