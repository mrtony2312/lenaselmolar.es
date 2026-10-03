@props(['product'])

@php
    $hasDiscount = !empty($product['old_price'])
        && (float) str_replace(',', '', $product['old_price']) > (float) str_replace(',', '', $product['price']);
    $inWishlist = in_array($product['id'], array_keys(Session::get('wishlist', [])));
@endphp

<div class="lv-product-card" data-product-id="{{ $product['id'] }}">
    <a href="{{ route('product.show', ['slug' => $product['slug']]) }}" class="lv-product-card__image-link">
        <div class="lv-product-card__image">
            <img loading="lazy" width="480" height="480" src="{{ asset($product['images'][0]) }}"
                class="{{ empty($product['hover_image']) ? 'image-no-effect' : 'image-effect attachment-shop_catalog' }}"
                alt="{{ $product['title'] }}" decoding="async">
            @if (!empty($product['hover_image']))
                <img loading="lazy" width="480" height="480" src="{{ asset($product['hover_image']) }}"
                    class="image-hover" alt="" decoding="async">
            @endif
        </div>
        @if ($hasDiscount)
            <span class="lv-product-card__badge">Oferta</span>
        @endif
    </a>

    <div class="lv-product-card__actions">
        <a href="javascript:void(0);" data-product-id="{{ $product['id'] }}"
            data-product-title="{{ $product['title'] }}" data-product-price="{{ $product['price'] }}"
            data-product-image="{{ asset($product['images'][0]) }}" data-product-slug="{{ $product['slug'] }}"
            class="lv-product-card__action add_to_cart_button ajax_add_to_cart"
            aria-label="Añadir al carrito: &ldquo;{{ $product['title'] }}&rdquo;">
            <i class="tb-icon tb-icon-bag-2"></i>
        </a>
        <a class="lv-product-card__action wishlist-button {{ $inWishlist ? 'wishlist-added' : '' }}"
            aria-label="Añadir a la lista de deseos: &ldquo;{{ $product['title'] }}&rdquo;"
            data-product-id="{{ $product['id'] }}" data-product-title="{{ $product['title'] }}"
            data-product-price="{{ $product['price'] }}" data-product-image="{{ asset($product['images'][0]) }}"
            data-product-slug="{{ $product['slug'] }}" href="#">
            <svg width="18" height="18" viewBox="0 0 24 24"
                fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z">
                </path>
            </svg>
            <span class="screen-reader-text">{{ $inWishlist ? 'En la lista' : 'Añadir a la lista de deseos' }}</span>
        </a>
        <a href="#" class="lv-product-card__action qview-button" title="Vista rápida"
            data-effect="mfp-move-from-top" data-product-id="{{ $product['id'] }}">
            <i class="tb-icon tb-icon-eye"></i>
        </a>
    </div>

    <div class="lv-product-card__body">
        <span class="lv-product-card__price">
            @if ($hasDiscount)
                <span class="lv-product-card__price-old">{{ \App\Support\Money::eur($product['old_price']) }}</span>
            @endif
            {{ \App\Support\Money::eur($product['price']) }}
        </span>
        @php
            $unitPriceLabel = \App\Support\UnitPricing::displayString(
                \App\Support\Money::toFloat($product['price']),
                isset($product['unit_measure_value']) ? (float) $product['unit_measure_value'] : null,
                $product['unit_measure_unit'] ?? null
            );
        @endphp
        @if ($unitPriceLabel)
            <span class="lv-product-card__unit-price">{{ $unitPriceLabel }}</span>
        @endif
        <h3 class="lv-product-card__title">
            <a href="{{ route('product.show', ['slug' => $product['slug']]) }}">{{ $product['title'] }}</a>
        </h3>
    </div>
</div>
