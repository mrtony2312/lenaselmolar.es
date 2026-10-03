<?php

/**
 * Google Merchant Center + storefront source of truth for NAP, shipping,
 * returns, currency and catalog mappings. Feed, JSON-LD and legal copy
 * must read from here so they cannot drift.
 */

return [

    'feed_token' => env('MERCHANT_FEED_TOKEN', ''),
    'currency' => env('MERCHANT_CURRENCY', 'EUR'),
    'target_country' => env('MERCHANT_TARGET_COUNTRY', 'ES'),
    'default_brand' => env('MERCHANT_DEFAULT_BRAND', ''),
    'feed_id_prefix' => 'lv-',

    // true ONLY once each old_price is documented as the lowest price really
    // charged in the 30 days before the discount. Otherwise no strike-through
    // price nor sale_price is published anywhere.
    'reference_prices_verified' => (bool) env('MERCHANT_REFERENCE_PRICES_VERIFIED', false),

    'nap' => [
        // Identidad confirmada de Leñas El Molar C.B.
        // Teléfono: no modificar (instrucción explícita — conservar el valor ya presente).
        // Email: no confirmado para esta entidad — no inventar.
        'legal_name' => 'Leñas El Molar C.B.',
        'commercial_name' => 'Leñas El Molar',
        'alternate_name' => 'Leñas y Carbones El Molar',
        'legal_form' => 'Comunidad de Bienes (C.B.)',
        'vat_id' => 'ESE85899003',
        'tax_id' => 'E85899003',
        'email' => '',
        'telephone' => '+34679245597',
        'telephone_display' => '+34 679 24 55 97',
        'street' => 'Calle de la Salud, 4',
        'postal_code' => '28710',
        'locality' => 'El Molar',
        'region' => 'Madrid',
        'country' => 'ES',
        'country_name' => 'España',
        'address_line' => 'Calle de la Salud, 4, 28710 El Molar (Madrid), España',
        'activity_since' => '2010',
        // Real company profiles only; empty = no icon in the footer.
        'facebook_url' => env('MERCHANT_FACEBOOK_URL', ''),
        'instagram_url' => env('MERCHANT_INSTAGRAM_URL', ''),
        'cnae' => '161',
        'cnae_label' => 'Aserrado y cepillado de la madera',
    ],

    /*
     | Single source of truth for delivery: storefront (PDP, cart, checkout,
     | legal pages), JSON-LD and the feed all read App\Support\ShippingPolicy,
     | which reads this block. Nothing about delivery is hard-coded elsewhere.
     |
     | A rate is only published (site + feed) when BOTH flags are true:
     |  - publish:            the business has fixed the price and delays below;
     |  - coverage_confirmed: the business really delivers at that price to the
     |                        whole of `country` (incl. islands, Ceuta, Melilla
     |                        for ES) — otherwise regions must be configured in
     |                        Merchant Center and here before confirming.
     | Until then every surface says "a confirmar" and the feed sends no
     | g:shipping (merchant:validate reports it as CRITICAL).
     */
    'shipping' => [
        'publish' => (bool) env('MERCHANT_SHIPPING_PUBLISH', false),
        'publish_transit' => (bool) env('MERCHANT_SHIPPING_PUBLISH_TRANSIT', false),
        'coverage_confirmed' => (bool) env('MERCHANT_SHIPPING_COVERAGE_CONFIRMED', false),
        'country' => env('MERCHANT_TARGET_COUNTRY', 'ES'),
        'service' => 'Estándar',
        'price' => env('MERCHANT_SHIPPING_PRICE', '0.00'),
        'handling_min' => (int) env('MERCHANT_HANDLING_MIN_DAYS', 1),
        'handling_max' => (int) env('MERCHANT_HANDLING_MAX_DAYS', 2),
        'transit_min' => (int) env('MERCHANT_TRANSIT_MIN_DAYS', 2),
        'transit_max' => (int) env('MERCHANT_TRANSIT_MAX_DAYS', 3),
    ],

    /*
     | Markets served by the feed. One entry per target country; the feed,
     | validator and JSON-LD read language/currency from here. Only Spain is
     | sold today — do not add a market without translated catalog content.
     */
    'markets' => [
        'ES' => ['country' => 'ES', 'language' => 'es', 'currency' => 'EUR', 'locale' => 'es_ES'],
    ],
    'default_market' => env('MERCHANT_TARGET_COUNTRY', 'ES'),

    // Official taxonomy file used by merchant:validate to check category IDs.
    'taxonomy_file' => resource_path('merchant/taxonomy-with-ids.es-ES.txt'),

    // Images smaller than this (either side) are reported. Google's hard
    // minimum is 100×100 (250×250 apparel); 500×500 is the recommended floor.
    'image_min_dimension' => 500,

    'returns' => [
        'days' => (int) env('MERCHANT_RETURN_DAYS', 14),
        'customer_pays_return_shipping' => true,
        'refund_days' => 14,
    ],

    /*
     | Official Google product taxonomy leaf IDs (numeric). Assigned by
     | storefront category, overridable per product via google_product_category.
     | https://support.google.com/merchants/answer/6324436
     */
    'google_product_category' => [
        'lena' => '625',
        'pellets-de-madera' => '625',
        'madera-densificada' => '625',
        'a-granel' => '625',
        'estufas-de-pellets' => '2639',
        'cocinas-de-lena' => '2639',
        'calderas-de-lena' => '3082',
    ],

    /*
     | Brand needles (longest first). Only names that already appear in
     | catalog titles — never invented manufacturer identities.
     |
     | A brand found this way is "inferred": it is sent, but merchant:validate
     | asks for it to be confirmed and stored in products.brand. Model names
     | (SAMANTHA, BESTIA, MATILDE, VEGA, ELITE, CERO, CHIP2, Temy, Moravia,
     | Hunter, Vulkan, Olimpia) and model-number guesses (BP-100 → FM) were
     | removed: a model is not a brand, and a brand absent from the product
     | data must not be deduced. Edilkamin stays: it is written in the title.
     */
    /*
     | Other manufacturer names known to appear in image file names of the
     | catalog. Used only by merchant:validate to detect a photo of another
     | brand's product — never sent as a brand.
     */
    'image_brand_tokens' => ['SunFire', 'Crépito', 'Termomont'],

    'brands' => [
        'Natural Energie' => 'Natural Energie',
        'Pellets Naturkraft' => 'Naturkraft',
        'Naturkraft' => 'Naturkraft',
        'Naturpellet' => 'Naturpellet',
        'Ardenforest' => 'Ardenforest',
        'Starforest' => 'Starforest',
        'Bioforestal' => 'Bioforestal',
        'Proxima Star' => 'Proxima Star',
        'MM Royal' => 'MM Royal',
        'Bio Energy' => 'Bio Energy',
        'Green Energy' => 'Green Energy',
        'Excellent pellets' => 'Excellent',
        'excellent pellets' => 'Excellent',
        'Edilkamin' => 'Edilkamin',
        'Woodstock' => 'Woodstock',
        'Coterram' => 'Coterram',
        'Valboval' => 'Valboval',
        'Van Roje' => 'Van Roje',
        'Limouzi' => 'Limouzi',
        'Vimasol' => 'Vimasol',
        'Nova Leña' => 'Nova Leña',
        'Mi Pellet' => 'Mi Pellet',
        'DIN Pellets' => 'DIN Pellets',
        'Pellet Gold' => 'Gold',
        'Pellet Bear' => 'Bear',
        'Pellet Badger' => 'Badger',
        'Pellet Helios' => 'Helios',
        'Palé Helios' => 'Helios',
        'Palser' => 'Palser',
        'CYL Pellet' => 'CYL',
        // Stoves: only MBS is unambiguous in the titles ("MBS Magnum", "MBS
        // Vesta"…). Temy (image files say "Termomont Temy Plus"), Moravia,
        // Hunter, Vulkan, Olimpia are model names whose manufacturer is not
        // in the data: store the confirmed brand in products.brand instead.
        'MBS' => 'MBS',
    ],
];
