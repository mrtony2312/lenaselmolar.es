@extends('layouts.app')

@section('title', __('Carrito'))

@push('styles')
    <link rel='stylesheet' id='wc-blocks-style-css'
          href='../wp-content/plugins/woocommerce/assets/client/blocks/wc-blocks8868.css?ver=wc-10.3.5'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='wc-blocks-style-all-products-css'
          href='../wp-content/plugins/woocommerce/assets/client/blocks/all-products8868.css?ver=wc-10.3.5'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='wc-blocks-style-cart-css'
          href='../wp-content/plugins/woocommerce/assets/client/blocks/cart8868.css?ver=wc-10.3.5'
          type='text/css' media='all'/>

@endpush

@section('content')
    @include('layouts.partials.navbar.public-show')

    <div id="wrapper-container" class="wrapper-container">
        <div id="tbay-main-content">
            <div class="title-not-breadcrumbs">
                <div class="container"><h1 class="page-title">Carrito</h1></div>
            </div>

            {{-- Section panier vide --}}
            <section id="main-container" class="container empty-cart-section"
                     style="{{ $isEmpty ? 'display: block;' : 'display: none;' }}">
                <div class="row">
                    <div id="main-content" class="main-page col-12">
                        <div id="main" class="site-main">
                            <div data-block-name="woocommerce/cart" class="wp-block-woocommerce-cart alignwide">
                                <div class="wp-block-woocommerce-empty-cart-block">
                                    <h2 class="wp-block-heading has-text-align-center with-empty-cart-icon wc-block-cart__empty-cart__title">
                                        ¡Tu carrito está vacío!
                                    </h2>

                                    <hr class="wp-block-separator has-alpha-channel-opacity is-style-dots">

                                    <h2 class="wp-block-heading has-text-align-center">Novedades de la tienda</h2>

                                    <div data-block-name="woocommerce/product-new" data-columns="4" data-rows="1"
                                         class="wc-block-grid wp-block-product-new wp-block-woocommerce-product-new wc-block-product-new has-4-columns">
                                        <ul class="wc-block-grid__products">
                                            @if($newProducts && count($newProducts) > 0)
                                                @foreach($newProducts as $product)
                                                    @php
                                                        $price = $product['price'] ?? 0;
                                                        $oldPrice = $product['old_price'] ?? null;

                                                        // Afficher avec 3 décimales
                                                        $formattedPrice = number_format($price, 2, ',', '.');
                                                        $formattedOldPrice = $oldPrice ? number_format($oldPrice, 2, ',', '.') : null
                                                    @endphp

                                                    <li class="wc-block-grid__product">
                                                        <a href="{{ route('product.show', ['slug' => $product['slug'] ?? '']) }}"
                                                           class="wc-block-grid__product-link">
                                                            @if($oldPrice && $oldPrice > $price)
                                                                <div class="wc-block-grid__product-onsale">
                                                                    <span aria-hidden="true">Oferta</span>
                                                                    <span
                                                                        class="screen-reader-text">Producto en oferta</span>
                                                                </div>
                                                            @endif
                                                            <div class="wc-block-grid__product-image">
                                                                <img loading="lazy" decoding="async" width="480"
                                                                     height="480"
                                                                     src="{{ asset($product['images'][0] ?? ($product['image'] ?? '')) }}"
                                                                     class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                                     alt="{{ $product['title'] ?? 'Producto' }}">
                                                            </div>
                                                            <div
                                                                class="wc-block-grid__product-title">{{ $product['title'] ?? 'Producto' }}</div>
                                                        </a>
                                                        <div class="wc-block-grid__product-price price">
                                                            @if($oldPrice && $oldPrice > $price)
                                                                <del aria-hidden="true">
                                                                    <span class="woocommerce-Price-amount amount">
                                                                        {{ $formattedOldPrice }}&nbsp;€
                                                                    </span>
                                                                </del>
                                                            @endif
                                                            <ins aria-hidden="true">
                                                                <span class="woocommerce-Price-amount amount">
                                                                    {{ $formattedPrice }}&nbsp;€
                                                                </span>
                                                            </ins>
                                                            <span class="screen-reader-text">
                                                                El precio actual es: {{ $formattedPrice }}&nbsp;€.
                                                            </span>
                                                            <small class="woocommerce-price-suffix">IVA incluido</small>
                                                        </div>

                                                        <div class="wp-block-button wc-block-grid__product-add-to-cart">
                                                            <a href="javascript:void(0);"
                                                               data-product-id="{{ $product['id'] ?? '' }}"
                                                               class="wp-block-button__link add_to_cart_button ajax_add_to_cart"
                                                               aria-label="Añadir al carrito: &ldquo;{{ $product['title'] ?? 'Producto' }}&rdquo;">
                                                                Añadir
                                                            </a>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="wc-block-grid__product">
                                                    <p>No hay ningún producto disponible en este momento.</p>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Section panier avec produits --}}
            <section id="main-container" class="container filled-cart-section"
                     style="{{ !$isEmpty ? 'display: block;' : 'display: none;' }}">
                <div class="row">
                    <div id="main-content" class="main-page col-12">
                        <div id="main" class="site-main">
                            <div data-block-name="woocommerce/cart" class="wp-block-woocommerce-cart alignwide">
                                <div class="with-scroll-to-top__scroll-point" aria-hidden="true"></div>
                                <div class="wc-block-components-notices"></div>
                                <div
                                    class="wc-block-components-notices__snackbar wc-block-components-notice-snackbar-list"
                                    tabindex="-1">
                                    <div></div>
                                </div>

                                <div
                                    class="wc-block-components-sidebar-layout wc-block-cart wp-block-woocommerce-filled-cart-block is-mobile">
                                    <div aria-hidden="true"
                                         style="position: absolute; inset: 0px; pointer-events: none; opacity: 0; overflow: hidden; z-index: -1;"></div>

                                    {{-- Liste des produits --}}
                                    <div
                                        class="wc-block-components-main wc-block-cart__main wp-block-woocommerce-cart-items-block">
                                        <table class="wc-block-cart-items wp-block-woocommerce-cart-line-items-block"
                                               tabindex="-1">

                                            <thead>
                                            <tr class="wc-block-cart-items__header">
                                                <th class="wc-block-cart-items__header-image"><span>Producto</span></th>
                                                <th class="wc-block-cart-items__header-total"><span>Total</span></th>
                                            </tr>
                                            </thead>
                                            <tbody id="cart-items-body">
                                            @if($cart && count($cart) > 0)
                                                @foreach($cart as $productId => $item)
                                                    @php
                                                        $itemPrice = $item['price'] ?? 0;
                                                        $itemQuantity = (int)($item['quantity'] ?? 0);
                                                        $itemTotal = $itemPrice * $itemQuantity;

                                                        // Afficher avec 3 décimales
                                                        $formattedItemPrice = number_format($itemPrice, 2, ',', '.');
                                                        $formattedItemTotal = number_format($itemTotal, 2, ',', '.')
                                                    @endphp

                                                    <tr class="wc-block-cart-items__row cart-item-row"
                                                        data-product-id="{{ $productId }}">
                                                        <td class="wc-block-cart-item__image pt-3" aria-hidden="true">
                                                            <a href="{{ route('product.show', ['slug' => $item['slug'] ?? '']) }}"
                                                               tabindex="-1">
                                                                @if (!empty($item['image']))
                                                                <img
                                                                    src="{{ asset($item['image']) }}"
                                                                    alt="{{ $item['title'] }}"
                                                                    class="cart-product-image">
                                                                @endif
                                                            </a>
                                                        </td>
                                                        <td class="wc-block-cart-item__product pt-3">
                                                            <div class="wc-block-cart-item__wrap">
                                                                <a class="wc-block-components-product-name"
                                                                   href="{{ route('product.show', ['slug' => $item['slug'] ?? '']) }}">
                                                                    {{ $item['title'] ?? 'Producto sin nombre' }}
                                                                </a>

                                                                @php
                                                                    // A reference price is only non-empty once
                                                                    // merchant.reference_prices_verified is set (Catalog).
                                                                    $lineOldPrice = $item['old_price'] !== '' ? \App\Support\Money::toFloat($item['old_price']) : null;
                                                                @endphp
                                                                <div class="wc-block-cart-item__prices"><span
                                                                        class="price wc-block-components-product-price">
                                                                    @if ($lineOldPrice && $lineOldPrice > $itemPrice)
                                                                        <span class="screen-reader-text">Precio anterior:</span><del
                                                                            class="wc-block-components-product-price__regular">{{ \App\Support\Money::format($lineOldPrice) }} €</del><span
                                                                            class="screen-reader-text">Precio con descuento:</span>
                                                                    @endif
                                                                    <ins class="wc-block-components-product-price__value">{{ $formattedItemPrice }} €</ins></span>
                                                                </div>
                                                                @unless ($item['available'])
                                                                    <p class="lv-cart-unavailable" style="color:#b42318;font-weight:600;">
                                                                        Agotado: elimina este artículo para poder finalizar la compra.
                                                                    </p>
                                                                @endunless

                                                                <div
                                                                    class="wc-block-components-product-metadata__description">
                                                                    <p style="font-size: 13px">{{ \Illuminate\Support\Str::limit($item['short_description'], 150, '...') }}</p>

                                                                </div>
                                                                <div class="wc-block-cart-item__quantity">
                                                                    <div class="quantity-selector">
                                                                        <button type="button" class="quantity-minus"
                                                                                data-product-id="{{ $productId }}">−
                                                                        </button>
                                                                        <input type="number" class="quantity-input"
                                                                               data-product-id="{{ $productId }}"
                                                                               value="{{ $itemQuantity }}" min="1">
                                                                        <button type="button" class="quantity-plus"
                                                                                data-product-id="{{ $productId }}">＋
                                                                        </button>
                                                                    </div>
                                                                    <button
                                                                        class="wc-block-cart-item__remove-link remove-item mt-3" style="display: block !important; "
                                                                        data-product-id="{{ $productId }}">
                                                                        Eliminar este artículo
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="wc-block-cart-item__total">
                                                            <div
                                                                class="wc-block-cart-item__total-price-and-sale-badge-wrapper">
                                                                <span class="price wc-block-components-product-price">
                                                                    <span
                                                                        class="wc-block-formatted-money-amount wc-block-components-formatted-money-amount wc-block-components-product-price__value item-total"
                                                                        data-total="{{ $itemTotal }}">
                                                                        {{ $formattedItemTotal }} €
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3" style="text-align: center; padding: 20px;">
                                                        El carrito está vacío.
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Résumé du panier --}}
                                    @if($cart && count($cart) > 0)
                                        <div
                                            class="wc-block-components-sidebar wc-block-cart__sidebar wp-block-woocommerce-cart-totals-block">
                                            <div class="wp-block-woocommerce-cart-order-summary-block">
                                                <h2 class="wp-block-woocommerce-cart-order-summary-heading-block wc-block-cart__totals-title">
                                                    Total del carrito
                                                </h2>
                                                <div class="wp-block-woocommerce-cart-order-summary-totals-block">
                                                    <div
                                                        class="wp-block-woocommerce-cart-order-summary-shipping-block wc-block-components-totals-wrapper">
                                                        <div class="wc-block-components-totals-shipping">
                                                            <div class="wc-block-components-totals-item"
                                                                 style="display: flex; justify-content: space-between">
                                                                <span class="wc-block-components-totals-item__label">Subtotal</span>
                                                                <span class="wc-block-components-totals-item__value" id="cart-subtotal-price">
                                                                    {{ $formattedSubtotal }} €
                                                                </span>
                                                            </div>
                                                            <div class="wc-block-components-totals-item"
                                                                 style="display: flex; justify-content: space-between">
                                                                <span class="wc-block-components-totals-item__label">Envío</span>
                                                                <span class="wc-block-components-totals-item__value">
                                                                    <strong>{{ $totals['shipping_label'] }}</strong>
                                                                </span>
                                                            </div>
                                                            <p style="font-size:13px;margin:6px 0 0;">{{ \App\Support\ShippingPolicy::summary() }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wc-block-components-totals-wrapper">
                                                    <div
                                                        class="wc-block-components-totals-item wc-block-components-totals-footer-item"
                                                        style="display: flex; justify-content: space-between">
                                                        <span class="wc-block-components-totals-item__label">{{ $totals['total_includes_shipping'] ? 'Total (IVA y envío incluidos)' : 'Total productos (IVA incluido, envío a confirmar)' }}</span>
                                                        <span class="wc-block-components-totals-item__value">
                                                            <span
                                                                class="wc-block-formatted-money-amount wc-block-components-formatted-money-amount wc-block-components-totals-footer-item-tax-value"
                                                                id="cart-total-price"
                                                                data-total="{{ $totalPrice }}">
                                                                {{ $formattedTotalPrice }} €
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="wc-block-cart__payment-options"></div>

                                            <div
                                                class="wc-block-cart__submit wp-block-woocommerce-proceed-to-checkout-block">
                                                <div aria-hidden="true"
                                                     style="inset: 0px; opacity: 0; pointer-events: none; position: absolute; z-index: -1;"></div>
                                                <div
                                                    class="wc-block-cart__submit-container wc-block-cart__submit-container--sticky"
                                                    style="background-color: rgb(255, 255, 255);">
                                                    <a href="{{ route('checkout') }}"
                                                       class="wc-block-components-button wp-element-button wc-block-cart__submit-button contained">
                                                        <div class="wc-block-components-button__text">Finalizar
                                                            compra
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @include('layouts.partials.footer.public')
@endsection

@push('scripts')

@endpush
