<?php

namespace App\DTO\Merchant;

use App\Domain\Merchant\Support\Availability;

/**
 * One offer, as rendered on the product page, in its JSON-LD and in the
 * Google feed. The three outputs are built from this object only.
 */
class GoogleProductData
{
    /**
     * @param  list<string>  $additionalImageLinks
     */
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public string $link,
        public string $imageLink,
        public array $additionalImageLinks,
        public string $price,
        public ?string $salePrice,
        public float $offerAmount,
        public string $currency,
        public Availability $availability,
        public string $condition,
        public ?string $brand,
        public ?string $gtin,
        public ?string $mpn,
        public bool $identifierExists,
        public bool $sendIdentifierExists,
        public ?string $itemGroupId,
        public ?string $color,
        public ?string $googleProductCategory,
        public ?string $productType,
        public ?string $unitPricingMeasure,
        public ?string $unitPricingBaseMeasure,
        public ?string $certificationCode,
        public string $shippingCountry,
        public string $shippingService,
        public ?string $shippingPrice,
        public int $minHandlingTime,
        public int $maxHandlingTime,
        public int $minTransitTime,
        public int $maxTransitTime,
        public int $returnDays,
        public bool $eligible,
        public string $sku,
        public bool $inStock,
        public bool $shippingConfirmed = false,
        public bool $shippingTimesConfirmed = false,
        public ?string $brandSource = null,
        public ?string $gtinSource = null,
        public int $productId = 0,
        public string $language = 'es',
    ) {}

    /**
     * JSON-LD Product + Offer. Price is the amount the customer pays now
     * (sale price when present) so it matches the PDP and g:sale_price.
     * Shipping details are only published once the rate is confirmed.
     *
     * @return array<string, mixed>
     */
    public function toJsonLd(): array
    {
        $nap = config('merchant.nap');

        $offer = [
            '@type' => 'Offer',
            'url' => $this->link,
            'sku' => $this->sku,
            'priceCurrency' => $this->currency,
            'price' => number_format($this->offerAmount, 2, '.', ''),
            'availability' => $this->availability->schemaUrl(),
            'itemCondition' => 'https://schema.org/NewCondition',
            'seller' => [
                '@type' => 'Organization',
                'name' => $nap['legal_name'] ?? config('app.name'),
            ],
            'hasMerchantReturnPolicy' => $this->returnPolicy(),
        ];

        if ($this->shippingConfirmed && $this->shippingPrice !== null) {
            $offer['shippingDetails'] = $this->shippingDetails();
        }

        $images = array_values(array_filter(array_merge([$this->imageLink], $this->additionalImageLinks)));

        $product = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $this->title,
            'description' => $this->description,
            'url' => $this->link,
            'image' => count($images) === 1 ? $images[0] : $images,
            'sku' => $this->sku,
            'offers' => $offer,
        ];

        if ($this->brand) {
            $product['brand'] = ['@type' => 'Brand', 'name' => $this->brand];
        }

        if ($this->gtin) {
            $key = match (strlen($this->gtin)) {
                8 => 'gtin8',
                12 => 'gtin12',
                13 => 'gtin13',
                14 => 'gtin14',
                default => 'gtin',
            };
            $product[$key] = $this->gtin;
        }

        if ($this->mpn) {
            $product['mpn'] = $this->mpn;
        }

        if ($this->productType) {
            $product['category'] = $this->productType;
        }

        return $product;
    }

    /**
     * @return array<string, mixed>
     */
    public function returnPolicy(): array
    {
        return [
            '@type' => 'MerchantReturnPolicy',
            'applicableCountry' => $this->shippingCountry,
            'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
            'merchantReturnDays' => $this->returnDays,
            'returnMethod' => 'https://schema.org/ReturnByMail',
            'returnFees' => 'https://schema.org/ReturnShippingFees',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function shippingDetails(): array
    {
        $details = [
            '@type' => 'OfferShippingDetails',
            'shippingRate' => [
                '@type' => 'MonetaryAmount',
                'value' => $this->shippingPrice,
                'currency' => $this->currency,
            ],
            'shippingDestination' => [
                '@type' => 'DefinedRegion',
                'addressCountry' => $this->shippingCountry,
            ],
        ];

        if ($this->shippingTimesConfirmed) {
            $details['deliveryTime'] = [
                '@type' => 'ShippingDeliveryTime',
                'handlingTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => $this->minHandlingTime,
                    'maxValue' => $this->maxHandlingTime,
                    'unitCode' => 'DAY',
                ],
                'transitTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => $this->minTransitTime,
                    'maxValue' => $this->maxTransitTime,
                    'unitCode' => 'DAY',
                ],
            ];
        }

        return $details;
    }
}
