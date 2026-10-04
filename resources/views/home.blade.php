@extends('layouts.app')

@section('title', 'Leña en El Molar | Leñas El Molar C.B.')
@section('meta_description', 'Leñas El Molar C.B. (Leñas y Carbones El Molar): venta de leña de calefacción, leña de encina, leña para estufa y chimenea, distribución de leña y carbón en El Molar (Madrid).')
@section('canonical', url('/'))

@push('styles')
    @vite(['resources/css/home.css'])
@endpush

@section('content')
    @include('layouts.partials.navbar.public')
    <div id="wrapper-container" class="wrapper-container">
        @include('section.slide')

        <div id="tbay-main-content">
            <section>
                <div class="row ">
                    <div id="main-content" class="main-page col-12">
                        <div id="main" class="site-main">
                            <div data-elementor-type="wp-page" data-elementor-id="145" class="elementor elementor-145">

                                <section
                                    class="elementor-section elementor-top-section elementor-element elementor-element-297be64 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                    data-id="297be64" data-element_type="section"
                                    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                    <div class="elementor-container elementor-column-gap-default">
                                        <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-7f4161d"
                                            data-id="7f4161d" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                <div class="elementor-element elementor-element-383a96d elementor-widget__width-initial elementor-widget elementor-widget-tbay-banner"
                                                    data-id="383a96d" data-element_type="widget"
                                                    data-widget_type="tbay-banner.default">
                                                    <div class="elementor-widget-container">
                                                        <div class="tbay-element tbay-element-banner cursor-pointer"
                                                            onclick="window.location.href=&#039;{{ route('loja') }}&#039;">
                                                            <div class="main-wrapp-img">
                                                                <div class="banner-image">
                                                                    <img loading="lazy" decoding="async" width="1248"
                                                                        height="832"
                                                                        src="{{ asset('wp-content/uploads/2025/10/765424359870807610.jpeg') }}"
                                                                        class="attachment-full size-full wp-image-5734"
                                                                        alt="Leña El Molar — leña y carbón" />
                                                                </div>
                                                            </div>
                                                            <div class="wrapper-content-banner">
                                                                <div class="content-banner">
                                                                    <h3 class="banner-tbay-title">
                                                                        <span class="title">Leñas El Molar C.B.</span>

                                                                        <span class="subtitle">Especialista en pellets de
                                                                            madera, leña y troncos comprimidos</span>
                                                                    </h3>


                                                                    <div class="banner-label"><span>Tienda</span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-1c16cf0"
                                            data-id="1c16cf0" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                <div class="elementor-element elementor-element-d01fa10 elementor-widget elementor-widget-tbay-banner"
                                                    data-id="d01fa10" data-element_type="widget"
                                                    data-widget_type="tbay-banner.default">
                                                    <div class="elementor-widget-container">
                                                        <div class="tbay-element tbay-element-banner cursor-pointer"
                                                            onclick="window.location.href=&#039;{{ route('contacto') }}&#039;">
                                                            <div class="main-wrapp-img">
                                                                <div class="banner-image">
                                                                    <img loading="lazy" decoding="async" width="1280"
                                                                        height="800"
                                                                        src="{{ asset('wp-content/uploads/2025/10/765424359870807621.jpg') }}"
                                                                        class="attachment-full size-full wp-image-5736"
                                                                        alt="Leña El Molar — leña y carbón" />
                                                                </div>
                                                            </div>
                                                            <div class="wrapper-content-banner">
                                                                <div class="content-banner">
                                                                    <h3 class="banner-tbay-title">
                                                                        <span class="title">Leñas El Molar C.B.</span>

                                                                        <span class="subtitle">La mejor experiencia de
                                                                            calefacción a leña.</span>
                                                                    </h3>

                                                                    <div class="banner-label"><span>¡Contáctenos!</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>


                                @if (isset($nuevosPelletsProducts) && count($nuevosPelletsProducts) > 0)
                                    <section class="lv-home-estufas">
                                        <div class="container">
                                            <div class="lv-home-estufas__head">
                                                <span class="lv-home-estufas__subtitle">Novedades</span>
                                                <h2 class="lv-home-estufas__title">Nuevos pellets</h2>
                                            </div>
                                            <div class="lv-product-grid">
                                                @foreach ($nuevosPelletsProducts as $pellet)
                                                    <x-product-card :product="$pellet" />
                                                @endforeach
                                            </div>
                                            <div class="lv-home-estufas__cta">
                                                <a class="lv-btn lv-btn--primary"
                                                    href="{{ route('category', ['category' => 'pellets-de-madera']) }}">Ver
                                                    todos los pellets</a>
                                            </div>
                                        </div>
                                    </section>
                                @endif


                                <section
                                    class="elementor-section elementor-top-section elementor-element elementor-element-82b5d97 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                    data-id="82b5d97" data-element_type="section"
                                    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                    <div class="elementor-container elementor-column-gap-default">
                                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-e39c852"
                                            data-id="e39c852" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                <div class="elementor-element elementor-element-b7b13ba elementor-product-v1 heading-tab-style-block elementor-widget elementor-widget-tbay-product-tabs"
                                                    data-id="b7b13ba" data-element_type="widget"
                                                    data-widget_type="tbay-product-tabs.default">
                                                    <div class="elementor-widget-container">

                                                        <div class="tbay-element tbay-element-product-tabs ajax-active">

                                                            <div class="wrapper-heading-tab">
                                                                <ul class="product-tabs-title tabs-list nav nav-tabs"
                                                                    data-atts="{&quot;categories&quot;:[&quot;pellets-de-madera&quot;],&quot;cat_operator&quot;:&quot;IN&quot;,&quot;limit&quot;:16,&quot;orderby&quot;:&quot;date&quot;,&quot;order&quot;:&quot;asc&quot;,&quot;product_style&quot;:&quot;v1&quot;,&quot;attr_row&quot;:&quot;class=\&quot;product-tabs row grid products\&quot; data-xlgdesktop=\&quot;4\&quot; data-desktop=\&quot;4\&quot; data-desktopsmall=\&quot;4\&quot; data-tablet=\&quot;3\&quot; data-landscape=\&quot;2\&quot; data-mobile=\&quot;2\&quot;&quot;}">
                                                                    <li>
                                                                        <a href="javascript:void(0)" data-bs-toggle="pill"
                                                                            data-bs-target="#best_selling-7t9jG-c11dc16"
                                                                            data-value="best_selling" class="active">PELLETS
                                                                            DE MADERA</a>
                                                                    </li>

                                                                </ul>
                                                            </div>

                                                            <div class="tbay-addon-content tab-content woocommerce">
                                                                <div class="tab-pane active active-content current"
                                                                    id="best_selling-7t9jG-c11dc16">


                                                                    <div class="product-tabs row grid products product-tabs products"
                                                                        data-xlgdesktop="4" data-desktop="4"
                                                                        data-desktopsmall="4" data-tablet="3"
                                                                        data-landscape="2" data-mobile="2">

                                                                        @foreach ($pelletProducts as $product)
                                                                            <div class="item">
                                                                                <div
                                                                                    class="products-grid product type-product post-{{ $product['id'] }} status-publish instock product_cat-pellets-de-madera has-post-thumbnail sale taxable shipping-taxable purchasable product-type-simple">
                                                                                    <div class="product-block grid product v1"
                                                                                        data-product-id="{{ $product['id'] }}">
                                                                                        <div class="product-content">

                                                                                            <div class="block-inner">
                                                                                                <figure class="image ">
                                                                                                    <a title="{{ $product['title'] }}"
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}"
                                                                                                        class="product-image">
                                                                                                        <img loading="lazy"
                                                                                                            decoding="async"
                                                                                                            width="480"
                                                                                                            height="480"
                                                                                                            src="{{ asset($product['images'][0]) }}"
                                                                                                            class="@if(empty($product['hover_image'])) image-no-effect @else image-effect attachment-shop_catalog @endif"
                                                                                                            alt="{{ $product['title'] }}" />
                                                                                                        @if ($product['hover_image'])
                                                                                                            <img loading="lazy"
                                                                                                                decoding="async"
                                                                                                                width="480"
                                                                                                                height="480"
                                                                                                                src="{{ asset($product['hover_image']) }}"
                                                                                                                class="image-hover"
                                                                                                                alt="" />
                                                                                                        @endif
                                                                                                    </a>
                                                                                                </figure>

                                                                                                <div class="group-buttons">
                                                                                                    <div class="add-cart"
                                                                                                        title="Añadir">
                                                                                                        <a href="javascript:void(0);"
                                                                                                            data-product-id="{{ $product['id'] ?? '' }}"
                                                                                                            class="wp-block-button__link add_to_cart_button ajax_add_to_cart"
                                                                                                            aria-label="Añadir al carrito: &ldquo;{{ $product['title'] ?? 'Producto' }}&rdquo;">
                                                                                                            <span
                                                                                                                class="title-cart">Añadir</span>
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-bag-2"></i>
                                                                                                        </a>
                                                                                                        <span
                                                                                                            id="woocommerce_loop_add_to_cart_link_describedby_{{ $product['id'] }}"
                                                                                                            class="screen-reader-text"></span>
                                                                                                    </div>
                                                                                                    <div class="button-wishlist shown-mobile"
                                                                                                        title="Lista de deseos">
                                                                                                        <div class="yith-add-to-wishlist-button-block yith-add-to-wishlist-button-block--initialized"
                                                                                                            data-attributes="{&quot;kind&quot;:&quot;button&quot;}">
                                                                                                            <a class="yith-wcwl-add-to-wishlist-button yith-wcwl-add-to-wishlist-button--anchor wishlist-button
            {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'wishlist-added' : '' }}"
                                                                                                                aria-label="Add To Wishlist: &ldquo;{{ $product['title'] }}&rdquo;"
                                                                                                                data-product-id="{{ $product['id'] }}"
                                                                                                                data-product-title="{{ $product['title'] }}"
                                                                                                                data-product-price="{{ $product['price'] }}"
                                                                                                                data-product-image="{{ asset($product['images'][0]) }}"
                                                                                                                data-product-slug="{{ $product['slug'] }}"
                                                                                                                href="#">
                                                                                                                <svg class="yith-wcwl-icon yith-wcwl-icon-svg yith-wcwl-add-to-wishlist-button-icon"
                                                                                                                    id="yith-wcwl-icon-heart-outline"
                                                                                                                    fill="{{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'red' : 'none' }}"
                                                                                                                    stroke-width="1.5"
                                                                                                                    stroke="currentColor"
                                                                                                                    viewBox="0 0 24 24"
                                                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                                                    <path
                                                                                                                        stroke-linecap="round"
                                                                                                                        stroke-linejoin="round"
                                                                                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z">
                                                                                                                    </path>
                                                                                                                </svg>
                                                                                                                <span
                                                                                                                    class="yith-wcwl-add-to-wishlist-button__label">
                                                                                                                    {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'En la lista' : 'Añadir a favoritos' }}
                                                                                                                </span>
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="tbay-quick-view">
                                                                                                        <a href="#"
                                                                                                            class="qview-button"
                                                                                                            title="Vista rápida"
                                                                                                            data-effect="mfp-move-from-top"
                                                                                                            data-product-id="{{ $product['id'] }}">
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-eye"></i>
                                                                                                            <span>Vista
                                                                                                                rápida</span>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>

                                                                                            </div>

                                                                                            @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                            <span class="onsale"><span
                                                                                                    class="saled">Oferta</span></span>
                                                                                            @endif

                                                                                            <div class="caption">
                                                                                                <span class="price">
                                                                                                    @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                                    <del
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['old_price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </del>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio original era:
                                                                                                        {{ \App\Support\Money::format($product['old_price']) }}&nbsp;&euro;.</span>
                                                                                                    @endif
                                                                                                    <ins
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </ins>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio actual es:
                                                                                                        {{ \App\Support\Money::format($product['price']) }}&nbsp;&euro;.</span>
                                                                                                    <small
                                                                                                        class="woocommerce-price-suffix">IVA
                                                                                                        incluido</small>
                                                                                                </span>

                                                                                                <h3 class="name">
                                                                                                    <a
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}">{{ $product['title'] }}</a>
                                                                                                </h3>

                                                                                                <div class="group-content">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="elementor-element elementor-element-b6e20e7 elementor-align-center elementor-widget elementor-widget-button"
                                                    data-id="b6e20e7" data-element_type="widget"
                                                    data-widget_type="button.default">
                                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                                        href="{{ route('category', ['category' => 'pellets-de-madera']) }}">
                                                        <span class="elementor-button-content-wrapper">
                                                            <span class="elementor-button-text">Ver todo</span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>


                                <section
                                    class="elementor-section elementor-top-section elementor-element elementor-element-231a483 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                    data-id="231a483" data-element_type="section"
                                    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                    <div class="elementor-container elementor-column-gap-default">
                                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-1164bba"
                                            data-id="1164bba" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                <div class="elementor-element elementor-element-73dc688 elementor-product-v1 heading-tab-style-block elementor-widget elementor-widget-tbay-product-tabs"
                                                    data-id="73dc688" data-element_type="widget"
                                                    data-widget_type="tbay-product-tabs.default">
                                                    <div class="elementor-widget-container">

                                                        <div class="tbay-element tbay-element-product-tabs ajax-active">

                                                            <div class="wrapper-heading-tab">
                                                                <ul class="product-tabs-title tabs-list nav nav-tabs"
                                                                    data-atts="{&quot;categories&quot;:[&quot;lenha&quot;],&quot;cat_operator&quot;:&quot;IN&quot;,&quot;limit&quot;:16,&quot;orderby&quot;:&quot;date&quot;,&quot;order&quot;:&quot;asc&quot;,&quot;product_style&quot;:&quot;v1&quot;,&quot;attr_row&quot;:&quot;class=\&quot;product-tabs row grid products\&quot; data-xlgdesktop=\&quot;4\&quot; data-desktop=\&quot;4\&quot; data-desktopsmall=\&quot;4\&quot; data-tablet=\&quot;3\&quot; data-landscape=\&quot;2\&quot; data-mobile=\&quot;2\&quot;&quot;}">
                                                                    <li>
                                                                        <a href="javascript:void(0)" data-bs-toggle="pill"
                                                                            data-bs-target="#best_selling-LkjyJ-c11dc16"
                                                                            data-value="best_selling"
                                                                            class="active">LEÑA</a>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="tbay-addon-content tab-content woocommerce">
                                                                <div class="tab-pane active active-content current"
                                                                    id="best_selling-LkjyJ-c11dc16">
                                                                    <div class="product-tabs row grid products product-tabs products"
                                                                        data-xlgdesktop="4" data-desktop="4"
                                                                        data-desktopsmall="4" data-tablet="3"
                                                                        data-landscape="2" data-mobile="2">

                                                                        @foreach ($lenhaProducts as $product)
                                                                            <div class="item">
                                                                                <div
                                                                                    class="products-grid product type-product post-{{ $product['id'] }} status-publish instock product_cat-lenha has-post-thumbnail sale taxable shipping-taxable purchasable product-type-simple">
                                                                                    <div class="product-block grid product v1"
                                                                                        data-product-id="{{ $product['id'] }}">
                                                                                        <div class="product-content">
                                                                                            <div class="block-inner">
                                                                                                <figure class="image ">
                                                                                                    <a title="{{ $product['title'] }}"
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}"
                                                                                                        class="product-image">
                                                                                                        <img loading="lazy"
                                                                                                            decoding="async"
                                                                                                            width="480"
                                                                                                            height="480"
                                                                                                            src="{{ asset($product['images'][0]) }}"
                                                                                                            class="{image-effect"
                                                                                                            alt="" />
                                                                                                        @if ($product['hover_image'])
                                                                                                            <img loading="lazy"
                                                                                                                decoding="async"
                                                                                                                width="225"
                                                                                                                height="225"
                                                                                                                src="{{ asset($product['hover_image']) }}"
                                                                                                                class="image-hover"
                                                                                                                alt="" />
                                                                                                        @endif
                                                                                                    </a>
                                                                                                </figure>

                                                                                                <div class="group-buttons">
                                                                                                    <div class="add-cart"
                                                                                                        title="Añadir">
                                                                                                        <a href="javascript:void(0);"
                                                                                                            data-product-id="{{ $product['id'] ?? '' }}"
                                                                                                            class="wp-block-button__link add_to_cart_button ajax_add_to_cart"
                                                                                                            aria-label="Añadir al carrito: &ldquo;{{ $product['title'] ?? 'Producto' }}&rdquo;">
                                                                                                            <span
                                                                                                                class="title-cart">Añadir</span>
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-bag-2"></i>
                                                                                                        </a>
                                                                                                        <span
                                                                                                            id="woocommerce_loop_add_to_cart_link_describedby_{{ $product['id'] }}"
                                                                                                            class="screen-reader-text"></span>
                                                                                                    </div>
                                                                                                    <div class="button-wishlist shown-mobile"
                                                                                                        title="Lista de deseos">
                                                                                                        <div class="yith-add-to-wishlist-button-block yith-add-to-wishlist-button-block--initialized"
                                                                                                            data-attributes="{&quot;kind&quot;:&quot;button&quot;}">
                                                                                                            <a class="yith-wcwl-add-to-wishlist-button yith-wcwl-add-to-wishlist-button--anchor wishlist-button
            {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'wishlist-added' : '' }}"
                                                                                                                aria-label="Add To Wishlist: &ldquo;{{ $product['title'] }}&rdquo;"
                                                                                                                data-product-id="{{ $product['id'] }}"
                                                                                                                data-product-title="{{ $product['title'] }}"
                                                                                                                data-product-price="{{ $product['price'] }}"
                                                                                                                data-product-image="{{ asset($product['images'][0]) }}"
                                                                                                                data-product-slug="{{ $product['slug'] }}"
                                                                                                                href="#">
                                                                                                                <svg class="yith-wcwl-icon yith-wcwl-icon-svg yith-wcwl-add-to-wishlist-button-icon"
                                                                                                                    id="yith-wcwl-icon-heart-outline"
                                                                                                                    fill="{{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'red' : 'none' }}"
                                                                                                                    stroke-width="1.5"
                                                                                                                    stroke="currentColor"
                                                                                                                    viewBox="0 0 24 24"
                                                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                                                    <path
                                                                                                                        stroke-linecap="round"
                                                                                                                        stroke-linejoin="round"
                                                                                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z">
                                                                                                                    </path>
                                                                                                                </svg>
                                                                                                                <span
                                                                                                                    class="yith-wcwl-add-to-wishlist-button__label">
                                                                                                                    {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'En la lista' : 'Añadir a favoritos' }}
                                                                                                                </span>
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="tbay-quick-view">
                                                                                                        <a href="#"
                                                                                                            class="qview-button"
                                                                                                            title="Vista rápida"
                                                                                                            data-effect="mfp-move-from-top"
                                                                                                            data-product-id="{{ $product['id'] }}">
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-eye"></i>
                                                                                                            <span>Vista
                                                                                                                rápida</span>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                            <span class="onsale"><span
                                                                                                    class="saled">Oferta</span></span>
                                                                                            @endif

                                                                                            <div class="caption">
                                                                                                <span class="price">
                                                                                                    @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                                    <del
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['old_price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </del>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio original era:
                                                                                                        {{ \App\Support\Money::format($product['old_price']) }}&nbsp;&euro;.</span>
                                                                                                    @endif
                                                                                                    <ins
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </ins>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio actual es:
                                                                                                        {{ \App\Support\Money::format($product['price']) }}&nbsp;&euro;.</span>
                                                                                                    <small
                                                                                                        class="woocommerce-price-suffix">IVA
                                                                                                        incluido</small>
                                                                                                </span>

                                                                                                <h3 class="name">
                                                                                                    <a
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}">{{ $product['title'] }}</a>
                                                                                                </h3>

                                                                                                <div class="group-content">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="elementor-element elementor-element-ec242d1 elementor-align-center elementor-widget elementor-widget-button"
                                                    data-id="ec242d1" data-element_type="widget"
                                                    data-widget_type="button.default">
                                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                                        href="{{ route('category', ['category' => 'lena']) }}">
                                                        <span class="elementor-button-content-wrapper">
                                                            <span class="elementor-button-text">Ver todo</span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>


                                <section
                                    class="elementor-section elementor-top-section elementor-element elementor-element-5d0e347b elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                    data-id="5d0e347b" data-element_type="section"
                                    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}">
                                    <div class="elementor-container elementor-column-gap-default">
                                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-46cfa7ea"
                                            data-id="46cfa7ea" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                <div class="elementor-element elementor-element-184e2302 elementor-align-center elementor-widget elementor-widget-button"
                                                    data-id="184e2302" data-element_type="widget"
                                                    data-widget_type="button.default">
                                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                                        href="{{ route('loja') }}">
                                                        <span class="elementor-button-content-wrapper">
                                                            <span class="elementor-button-text">Tienda</span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>


                                <section
                                    class="elementor-section elementor-top-section elementor-element elementor-element-7cdc75f elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                    data-id="7cdc75f" data-element_type="section"
                                    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                    <div class="elementor-container elementor-column-gap-default">
                                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-331df95"
                                            data-id="331df95" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                <div class="elementor-element elementor-element-118f1cb elementor-product-v1 heading-tab-style-block elementor-widget elementor-widget-tbay-product-tabs"
                                                    data-id="118f1cb" data-element_type="widget"
                                                    data-widget_type="tbay-product-tabs.default">
                                                    <div class="elementor-widget-container">

                                                        <div class="tbay-element tbay-element-product-tabs ajax-active">

                                                            <div class="wrapper-heading-tab">
                                                                <ul class="product-tabs-title tabs-list nav nav-tabs"
                                                                    data-atts="{&quot;categories&quot;:[&quot;cocinas-de-lena&quot;],&quot;cat_operator&quot;:&quot;IN&quot;,&quot;limit&quot;:12,&quot;orderby&quot;:&quot;date&quot;,&quot;order&quot;:&quot;asc&quot;,&quot;product_style&quot;:&quot;v1&quot;,&quot;attr_row&quot;:&quot;class=\&quot;product-tabs row grid products\&quot; data-xlgdesktop=\&quot;4\&quot; data-desktop=\&quot;4\&quot; data-desktopsmall=\&quot;4\&quot; data-tablet=\&quot;3\&quot; data-landscape=\&quot;2\&quot; data-mobile=\&quot;2\&quot;&quot;}">
                                                                    <li>
                                                                        <a href="javascript:void(0)" data-bs-toggle="pill"
                                                                            data-bs-target="#best_selling-kAljt-c11dc16"
                                                                            data-value="best_selling" class="active">COCINAS
                                                                            DE LEÑA</a>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="tbay-addon-content tab-content woocommerce">
                                                                <div class="tab-pane active active-content current"
                                                                    id="best_selling-kAljt-c11dc16">
                                                                    <div class="product-tabs row grid products product-tabs products"
                                                                        data-xlgdesktop="4" data-desktop="4"
                                                                        data-desktopsmall="4" data-tablet="3"
                                                                        data-landscape="2" data-mobile="2">

                                                                        @foreach ($chefProducts as $product)
                                                                            <div class="item">
                                                                                <div
                                                                                    class="products-grid product type-product post-{{ $product['id'] }} status-publish instock product_cat-cocinas-de-lena has-post-thumbnail sale taxable shipping-taxable purchasable product-type-simple">
                                                                                    <div class="product-block grid product v1"
                                                                                        data-product-id="{{ $product['id'] }}">
                                                                                        <div class="product-content">
                                                                                            <div class="block-inner">
                                                                                                <figure class="image ">
                                                                                                    <a title="{{ $product['title'] }}"
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}"
                                                                                                        class="product-image">
                                                                                                        <img loading="lazy"
                                                                                                            decoding="async"
                                                                                                            width="480"
                                                                                                            height="480"
                                                                                                            src="{{ asset($product['images'][0]) }}"
                                                                                                            class="@if(empty($product['hover_image'])) image-no-effect @else image-effect attachment-shop_catalog @endif"
                                                                                                            alt="{{ $product['title'] }}" />
                                                                                                        @if ($product['hover_image'])
                                                                                                            <img loading="lazy"
                                                                                                                decoding="async"
                                                                                                                width="480"
                                                                                                                height="480"
                                                                                                                src="{{ asset($product['hover_image']) }}"
                                                                                                                class="image-hover"
                                                                                                                alt="" />
                                                                                                        @endif
                                                                                                    </a>
                                                                                                </figure>

                                                                                                <div class="group-buttons">
                                                                                                    <div class="add-cart"
                                                                                                        title="Añadir">
                                                                                                        <a href="javascript:void(0);"
                                                                                                            data-product-id="{{ $product['id'] ?? '' }}"
                                                                                                            class="wp-block-button__link add_to_cart_button ajax_add_to_cart"
                                                                                                            aria-label="Añadir al carrito: &ldquo;{{ $product['title'] ?? 'Producto' }}&rdquo;">
                                                                                                            <span
                                                                                                                class="title-cart">Añadir</span>
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-bag-2"></i>
                                                                                                        </a>
                                                                                                        <span
                                                                                                            id="woocommerce_loop_add_to_cart_link_describedby_{{ $product['id'] }}"
                                                                                                            class="screen-reader-text"></span>
                                                                                                    </div>
                                                                                                    <div class="button-wishlist shown-mobile"
                                                                                                        title="Lista de deseos">
                                                                                                        <div class="yith-add-to-wishlist-button-block yith-add-to-wishlist-button-block--initialized"
                                                                                                            data-attributes="{&quot;kind&quot;:&quot;button&quot;}">
                                                                                                            <a class="yith-wcwl-add-to-wishlist-button yith-wcwl-add-to-wishlist-button--anchor wishlist-button
            {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'wishlist-added' : '' }}"
                                                                                                                aria-label="Add To Wishlist: &ldquo;{{ $product['title'] }}&rdquo;"
                                                                                                                data-product-id="{{ $product['id'] }}"
                                                                                                                data-product-title="{{ $product['title'] }}"
                                                                                                                data-product-price="{{ $product['price'] }}"
                                                                                                                data-product-image="{{ asset($product['images'][0]) }}"
                                                                                                                data-product-slug="{{ $product['slug'] }}"
                                                                                                                href="#">
                                                                                                                <svg class="yith-wcwl-icon yith-wcwl-icon-svg yith-wcwl-add-to-wishlist-button-icon"
                                                                                                                    id="yith-wcwl-icon-heart-outline"
                                                                                                                    fill="{{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'red' : 'none' }}"
                                                                                                                    stroke-width="1.5"
                                                                                                                    stroke="currentColor"
                                                                                                                    viewBox="0 0 24 24"
                                                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                                                    <path
                                                                                                                        stroke-linecap="round"
                                                                                                                        stroke-linejoin="round"
                                                                                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z">
                                                                                                                    </path>
                                                                                                                </svg>
                                                                                                                <span
                                                                                                                    class="yith-wcwl-add-to-wishlist-button__label">
                                                                                                                    {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'En la lista' : 'Añadir a favoritos' }}
                                                                                                                </span>
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="tbay-quick-view">
                                                                                                        <a href="#"
                                                                                                            class="qview-button"
                                                                                                            title="Vista rápida"
                                                                                                            data-effect="mfp-move-from-top"
                                                                                                            data-product-id="{{ $product['id'] }}">
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-eye"></i>
                                                                                                            <span>Vista
                                                                                                                rápida</span>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                            <span class="onsale"><span
                                                                                                    class="saled">Oferta</span></span>
                                                                                            @endif

                                                                                            <div class="caption">
                                                                                                <span class="price">
                                                                                                    @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                                    <del
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['old_price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </del>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio original era:
                                                                                                        {{ \App\Support\Money::format($product['old_price']) }}&nbsp;&euro;.</span>
                                                                                                    @endif
                                                                                                    <ins
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </ins>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio actual es:
                                                                                                        {{ \App\Support\Money::format($product['price']) }}&nbsp;&euro;.</span>
                                                                                                    <small
                                                                                                        class="woocommerce-price-suffix">IVA
                                                                                                        incluido</small>
                                                                                                </span>

                                                                                                <h3 class="name">
                                                                                                    <a
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}">{{ $product['title'] }}</a>
                                                                                                </h3>

                                                                                                <div class="group-content">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="elementor-element elementor-element-4a52e6d elementor-align-center elementor-widget elementor-widget-button"
                                                    data-id="4a52e6d" data-element_type="widget"
                                                    data-widget_type="button.default">
                                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                                        href="{{ route('category', ['category' => 'cocinas-de-lena']) }}">
                                                        <span class="elementor-button-content-wrapper">
                                                            <span class="elementor-button-text">Ver todo</span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>


                                <section
                                    class="elementor-section elementor-top-section elementor-element elementor-element-8b9e869 elementor-section-full_width elementor-section-stretched elementor-section-height-default elementor-section-height-default"
                                    data-id="8b9e869" data-element_type="section"
                                    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}">
                                    <div class="elementor-container elementor-column-gap-default">
                                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-65ed9c0"
                                            data-id="65ed9c0" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                <div class="elementor-element elementor-element-1e44416 elementor-widget elementor-widget-text-editor"
                                                    data-id="1e44416" data-element_type="widget"
                                                    data-widget_type="text-editor.default">
                                                    <p>Estamos aqui para si</p>
                                                </div>
                                                <section
                                                    class="elementor-section elementor-inner-section elementor-element elementor-element-fea20bc elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                                    data-id="fea20bc" data-element_type="section">
                                                    <div class="elementor-container elementor-column-gap-default">
                                                        <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-9be4837"
                                                            data-id="9be4837" data-element_type="column">
                                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                                <div class="elementor-element elementor-element-5b0472d elementor-widget elementor-widget-tbay-banner"
                                                                    data-id="5b0472d" data-element_type="widget"
                                                                    data-widget_type="tbay-banner.default">
                                                                    <div class="elementor-widget-container">
                                                                        <div class="tbay-element tbay-element-banner cursor-pointer"
                                                                            onclick="window.location.href=&#039;{{ route('contacto') }}m//&#039;">
                                                                            <div class="main-wrapp-img">
                                                                                <div class="banner-image">
                                                                                    <img loading="lazy" decoding="async"
                                                                                        width="1280" height="800"
                                                                                        src="wp-content/uploads/2025/10/765424359870807617.jpg"
                                                                                        class="attachment-full size-full wp-image-5738"
                                                                                        alt="Leña El Molar — leña y carbón" />
                                                                                </div>
                                                                            </div>
                                                                            <div class="wrapper-content-banner">
                                                                                <div class="content-banner">
                                                                                    <h3 class="banner-tbay-title">
                                                                                        <span class="title">Leñas El Molar C.B.</span>

                                                                                        <span class="subtitle">¿Buscas un
                                                                                            proveedor de PELLETS?</span>
                                                                                    </h3>


                                                                                    <div class="banner-label">
                                                                                        <span>¡CONTÁCTANOS!</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </section>


                                <div class="elementor-element elementor-element-4406292 e-flex e-con-boxed e-con e-parent"
                                    data-id="4406292" data-element_type="container">
                                    <div class="e-con-inner">
                                        <div class="elementor-element elementor-element-535d3b6 elementor-widget elementor-widget-spacer"
                                            data-id="535d3b6" data-element_type="widget"
                                            data-widget_type="spacer.default">
                                            <div class="elementor-spacer">
                                                <div class="elementor-spacer-inner"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <section
                                    class="elementor-section elementor-top-section elementor-element elementor-element-a01035d elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                    data-id="a01035d" data-element_type="section"
                                    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                    <div class="elementor-container elementor-column-gap-default">
                                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-5191e22"
                                            data-id="5191e22" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                <div class="elementor-element elementor-element-98a464d elementor-product-v1 heading-tab-style-block elementor-widget elementor-widget-tbay-product-tabs"
                                                    data-id="98a464d" data-element_type="widget"
                                                    data-widget_type="tbay-product-tabs.default">
                                                    <div class="elementor-widget-container">

                                                        <div class="tbay-element tbay-element-product-tabs ajax-active">

                                                            <div class="wrapper-heading-tab">
                                                                <ul class="product-tabs-title tabs-list nav nav-tabs"
                                                                    data-atts="{&quot;categories&quot;:[&quot;madera-densificada&quot;],&quot;cat_operator&quot;:&quot;IN&quot;,&quot;limit&quot;:4,&quot;orderby&quot;:&quot;date&quot;,&quot;order&quot;:&quot;asc&quot;,&quot;product_style&quot;:&quot;v1&quot;,&quot;attr_row&quot;:&quot;class=\&quot;product-tabs row grid products\&quot; data-xlgdesktop=\&quot;4\&quot; data-desktop=\&quot;4\&quot; data-desktopsmall=\&quot;4\&quot; data-tablet=\&quot;3\&quot; data-landscape=\&quot;2\&quot; data-mobile=\&quot;2\&quot;&quot;}">
                                                                    <li>
                                                                        <a href="javascript:void(0)" data-bs-toggle="pill"
                                                                            data-bs-target="#best_selling-ktTFY-c11dc16"
                                                                            data-value="best_selling"
                                                                            class="active">MADERA
                                                                            DENSIFICADA</a>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="tbay-addon-content tab-content woocommerce">
                                                                <div class="tab-pane active active-content current"
                                                                    id="best_selling-ktTFY-c11dc16">
                                                                    <div class="product-tabs row grid products product-tabs products"
                                                                        data-xlgdesktop="4" data-desktop="4"
                                                                        data-desktopsmall="4" data-tablet="3"
                                                                        data-landscape="2" data-mobile="2">

                                                                        @foreach ($compactadaProducts as $product)
                                                                            <div class="item">
                                                                                <div
                                                                                    class="products-grid product type-product post-{{ $product['id'] }} status-publish instock product_cat-madera-densificada has-post-thumbnail sale taxable shipping-taxable purchasable product-type-simple">
                                                                                    <div class="product-block grid product v1"
                                                                                        data-product-id="{{ $product['id'] }}">
                                                                                        <div class="product-content">
                                                                                            <div class="block-inner">
                                                                                                <figure class="image ">
                                                                                                    <a title="{{ html_entity_decode($product['title'], ENT_QUOTES, 'UTF-8') }}"
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}"
                                                                                                        class="product-image">
                                                                                                        <img loading="lazy"
                                                                                                            decoding="async"
                                                                                                            width="480"
                                                                                                            height="480"
                                                                                                            src="{{ asset($product['images'][0]) }}"
                                                                                                            class="@if(empty($product['hover_image'])) image-no-effect @else image-effect attachment-shop_catalog @endif"
                                                                                                            alt="{{ $product['title'] }}" />
                                                                                                        @if ($product['hover_image'])
                                                                                                            <img loading="lazy"
                                                                                                                decoding="async"
                                                                                                                width="480"
                                                                                                                height="480"
                                                                                                                src="{{ asset($product['hover_image']) }}"
                                                                                                                class="image-hover"
                                                                                                                alt="" />
                                                                                                        @endif
                                                                                                    </a>
                                                                                                </figure>

                                                                                                <div class="group-buttons">
                                                                                                    <div class="add-cart"
                                                                                                        title="Añadir">
                                                                                                        <a href="javascript:void(0);"
                                                                                                            data-product-id="{{ $product['id'] ?? '' }}"
                                                                                                            class="wp-block-button__link add_to_cart_button ajax_add_to_cart"
                                                                                                            aria-label="Añadir al carrito: &ldquo;{{ $product['title'] ?? 'Producto' }}&rdquo;">
                                                                                                            <span
                                                                                                                class="title-cart">Añadir</span>
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-bag-2"></i>
                                                                                                        </a>
                                                                                                        <span
                                                                                                            id="woocommerce_loop_add_to_cart_link_describedby_{{ $product['id'] }}"
                                                                                                            class="screen-reader-text"></span>
                                                                                                    </div>
                                                                                                    <div class="button-wishlist shown-mobile"
                                                                                                        title="Lista de deseos">
                                                                                                        <div class="yith-add-to-wishlist-button-block yith-add-to-wishlist-button-block--initialized"
                                                                                                            data-attributes="{&quot;kind&quot;:&quot;button&quot;}">
                                                                                                            <a class="yith-wcwl-add-to-wishlist-button yith-wcwl-add-to-wishlist-button--anchor wishlist-button
            {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'wishlist-added' : '' }}"
                                                                                                                aria-label="Add To Wishlist: &ldquo;{{ $product['title'] }}&rdquo;"
                                                                                                                data-product-id="{{ $product['id'] }}"
                                                                                                                data-product-title="{{ $product['title'] }}"
                                                                                                                data-product-price="{{ $product['price'] }}"
                                                                                                                data-product-image="{{ asset($product['images'][0]) }}"
                                                                                                                data-product-slug="{{ $product['slug'] }}"
                                                                                                                href="#">
                                                                                                                <svg class="yith-wcwl-icon yith-wcwl-icon-svg yith-wcwl-add-to-wishlist-button-icon"
                                                                                                                    id="yith-wcwl-icon-heart-outline"
                                                                                                                    fill="{{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'red' : 'none' }}"
                                                                                                                    stroke-width="1.5"
                                                                                                                    stroke="currentColor"
                                                                                                                    viewBox="0 0 24 24"
                                                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                                                    <path
                                                                                                                        stroke-linecap="round"
                                                                                                                        stroke-linejoin="round"
                                                                                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z">
                                                                                                                    </path>
                                                                                                                </svg>
                                                                                                                <span
                                                                                                                    class="yith-wcwl-add-to-wishlist-button__label">
                                                                                                                    {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'En la lista' : 'Añadir a favoritos' }}
                                                                                                                </span>
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="tbay-quick-view">
                                                                                                        <a href="#"
                                                                                                            class="qview-button"
                                                                                                            title="Vista rápida"
                                                                                                            data-effect="mfp-move-from-top"
                                                                                                            data-product-id="{{ $product['id'] }}">
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-eye"></i>
                                                                                                            <span>Vista
                                                                                                                rápida</span>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                            <span class="onsale"><span
                                                                                                    class="saled">Oferta</span></span>
                                                                                            @endif

                                                                                            <div class="caption">
                                                                                                <span class="price">
                                                                                                    @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                                    <del
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['old_price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </del>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio original era:
                                                                                                        {{ \App\Support\Money::format($product['old_price']) }}&nbsp;&euro;.</span>
                                                                                                    @endif
                                                                                                    <ins
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </ins>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio actual es:
                                                                                                        {{ \App\Support\Money::format($product['price']) }}&nbsp;&euro;.</span>
                                                                                                    <small
                                                                                                        class="woocommerce-price-suffix">IVA
                                                                                                        incluido</small>
                                                                                                </span>

                                                                                                <h3 class="name">
                                                                                                    <a
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}">{{ html_entity_decode($product['title'], ENT_QUOTES, 'UTF-8') }}</a>
                                                                                                </h3>

                                                                                                <div class="group-content">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="elementor-element elementor-element-cb12240 elementor-align-center elementor-widget elementor-widget-button"
                                                    data-id="cb12240" data-element_type="widget"
                                                    data-widget_type="button.default">
                                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                                        href="{{ route('category', ['category' => 'madera-densificada']) }}">
                                                        <span class="elementor-button-content-wrapper">
                                                            <span class="elementor-button-text">Ver todo</span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section
                                    class="elementor-section elementor-top-section elementor-element elementor-element-5f22068 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                    data-id="5f22068" data-element_type="section"
                                    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                    <div class="elementor-container elementor-column-gap-default">
                                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-edf00f8"
                                            data-id="edf00f8" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                <div class="elementor-element elementor-element-1a64f95 elementor-product-v1 heading-tab-style-block elementor-widget elementor-widget-tbay-product-tabs"
                                                    data-id="1a64f95" data-element_type="widget"
                                                    data-widget_type="tbay-product-tabs.default">
                                                    <div class="elementor-widget-container">

                                                        <div class="tbay-element tbay-element-product-tabs ajax-active">

                                                            <div class="wrapper-heading-tab">
                                                                <ul class="product-tabs-title tabs-list nav nav-tabs"
                                                                    data-atts="{&quot;categories&quot;:[&quot;calderas-de-lena&quot;],&quot;cat_operator&quot;:&quot;IN&quot;,&quot;limit&quot;:4,&quot;orderby&quot;:&quot;date&quot;,&quot;order&quot;:&quot;asc&quot;,&quot;product_style&quot;:&quot;v1&quot;,&quot;attr_row&quot;:&quot;class=\&quot;product-tabs row grid products\&quot; data-xlgdesktop=\&quot;4\&quot; data-desktop=\&quot;4\&quot; data-desktopsmall=\&quot;4\&quot; data-tablet=\&quot;3\&quot; data-landscape=\&quot;2\&quot; data-mobile=\&quot;2\&quot;&quot;}">
                                                                    <li>
                                                                        <a href="javascript:void(0)" data-bs-toggle="pill"
                                                                            data-bs-target="#best_selling-P94n5-c11dc16"
                                                                            data-value="best_selling"
                                                                            class="active">CALDERAS
                                                                            DE LEÑA</a>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="tbay-addon-content tab-content woocommerce">
                                                                <div class="tab-pane active active-content current"
                                                                    id="best_selling-P94n5-c11dc16">
                                                                    <div class="product-tabs row grid products product-tabs products"
                                                                        data-xlgdesktop="4" data-desktop="4"
                                                                        data-desktopsmall="4" data-tablet="3"
                                                                        data-landscape="2" data-mobile="2">

                                                                        @foreach ($caldeiraProducts as $product)
                                                                            <div class="item">
                                                                                <div
                                                                                    class="products-grid product type-product post-{{ $product['id'] }} status-publish instock product_cat-calderas-de-lena has-post-thumbnail sale taxable shipping-taxable purchasable product-type-simple">
                                                                                    <div class="product-block grid product v1"
                                                                                        data-product-id="{{ $product['id'] }}">
                                                                                        <div class="product-content">
                                                                                            <div class="block-inner">
                                                                                                <figure class="image ">
                                                                                                    <a title="{{ $product['title'] }}"
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}"
                                                                                                        class="product-image">
                                                                                                        <img loading="lazy"
                                                                                                            decoding="async"
                                                                                                            width="480"
                                                                                                            height="480"
                                                                                                            src="{{ asset($product['images'][0]) }}"
                                                                                                            class="@if(empty($product['hover_image'])) image-no-effect @else image-effect attachment-shop_catalog @endif"
                                                                                                            alt="{{ $product['title'] }}" />
                                                                                                        @if ($product['hover_image'])
                                                                                                            <img loading="lazy"
                                                                                                                decoding="async"
                                                                                                                width="480"
                                                                                                                height="480"
                                                                                                                src="{{ asset($product['hover_image']) }}"
                                                                                                                class="image-hover"
                                                                                                                alt="" />
                                                                                                        @endif
                                                                                                    </a>
                                                                                                </figure>

                                                                                                <div class="group-buttons">
                                                                                                    <div class="add-cart"
                                                                                                        title="Añadir">
                                                                                                        <a href="javascript:void(0);"
                                                                                                            data-product-id="{{ $product['id'] ?? '' }}"
                                                                                                            class="wp-block-button__link add_to_cart_button ajax_add_to_cart"
                                                                                                            aria-label="Añadir al carrito: &ldquo;{{ $product['title'] ?? 'Producto' }}&rdquo;">
                                                                                                            <span
                                                                                                                class="title-cart">Añadir</span>
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-bag-2"></i>
                                                                                                        </a>
                                                                                                        <span
                                                                                                            id="woocommerce_loop_add_to_cart_link_describedby_{{ $product['id'] }}"
                                                                                                            class="screen-reader-text"></span>
                                                                                                    </div>
                                                                                                    <div class="button-wishlist shown-mobile"
                                                                                                        title="Lista de deseos">
                                                                                                        <div class="yith-add-to-wishlist-button-block yith-add-to-wishlist-button-block--initialized"
                                                                                                            data-attributes="{&quot;kind&quot;:&quot;button&quot;}">
                                                                                                            <a class="yith-wcwl-add-to-wishlist-button yith-wcwl-add-to-wishlist-button--anchor wishlist-button
            {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'wishlist-added' : '' }}"
                                                                                                                aria-label="Add To Wishlist: &ldquo;{{ $product['title'] }}&rdquo;"
                                                                                                                data-product-id="{{ $product['id'] }}"
                                                                                                                data-product-title="{{ $product['title'] }}"
                                                                                                                data-product-price="{{ $product['price'] }}"
                                                                                                                data-product-image="{{ asset($product['images'][0]) }}"
                                                                                                                data-product-slug="{{ $product['slug'] }}"
                                                                                                                href="#">
                                                                                                                <svg class="yith-wcwl-icon yith-wcwl-icon-svg yith-wcwl-add-to-wishlist-button-icon"
                                                                                                                    id="yith-wcwl-icon-heart-outline"
                                                                                                                    fill="{{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'red' : 'none' }}"
                                                                                                                    stroke-width="1.5"
                                                                                                                    stroke="currentColor"
                                                                                                                    viewBox="0 0 24 24"
                                                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                                                    <path
                                                                                                                        stroke-linecap="round"
                                                                                                                        stroke-linejoin="round"
                                                                                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z">
                                                                                                                    </path>
                                                                                                                </svg>
                                                                                                                <span
                                                                                                                    class="yith-wcwl-add-to-wishlist-button__label">
                                                                                                                    {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'En la lista' : 'Añadir a favoritos' }}
                                                                                                                </span>
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="tbay-quick-view">
                                                                                                        <a href="#"
                                                                                                            class="qview-button"
                                                                                                            title="Vista rápida"
                                                                                                            data-effect="mfp-move-from-top"
                                                                                                            data-product-id="{{ $product['id'] }}">
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-eye"></i>
                                                                                                            <span>Vista
                                                                                                                rápida</span>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                            <span class="onsale"><span
                                                                                                    class="saled">Oferta</span></span>
                                                                                            @endif

                                                                                            <div class="caption">
                                                                                                <span class="price">
                                                                                                    @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                                    <del
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['old_price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </del>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio original era:
                                                                                                        {{ \App\Support\Money::format($product['old_price']) }}&nbsp;&euro;.</span>
                                                                                                    @endif
                                                                                                    <ins
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </ins>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio actual es:
                                                                                                        {{ \App\Support\Money::format($product['price']) }}&nbsp;&euro;.</span>
                                                                                                    <small
                                                                                                        class="woocommerce-price-suffix">IVA
                                                                                                        incluido</small>
                                                                                                </span>

                                                                                                <h3 class="name">
                                                                                                    <a
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}">{{ $product['title'] }}</a>
                                                                                                </h3>

                                                                                                <div class="group-content">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="elementor-element elementor-element-6f53353 elementor-align-center elementor-widget elementor-widget-button"
                                                    data-id="6f53353" data-element_type="widget"
                                                    data-widget_type="button.default">
                                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                                        href="{{ route('category', ['category' => 'calderas-de-lena']) }}">
                                                        <span class="elementor-button-content-wrapper">
                                                            <span class="elementor-button-text">Ver todo</span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>


                                <section
                                    class="elementor-section elementor-top-section elementor-element elementor-element-cbfc30e elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                    data-id="cbfc30e" data-element_type="section"
                                    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                    <div class="elementor-container elementor-column-gap-default">
                                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-104d36d"
                                            data-id="104d36d" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                <div class="elementor-element elementor-element-1d0cd78 elementor-product-v1 heading-tab-style-block elementor-widget elementor-widget-tbay-product-tabs"
                                                    data-id="1d0cd78" data-element_type="widget"
                                                    data-widget_type="tbay-product-tabs.default">
                                                    <div class="elementor-widget-container">

                                                        <div class="tbay-element tbay-element-product-tabs ajax-active">

                                                            <div class="wrapper-heading-tab">
                                                                <ul class="product-tabs-title tabs-list nav nav-tabs"
                                                                    data-atts="{&quot;categories&quot;:[&quot;a-granel&quot;],&quot;cat_operator&quot;:&quot;IN&quot;,&quot;limit&quot;:8,&quot;orderby&quot;:&quot;date&quot;,&quot;order&quot;:&quot;asc&quot;,&quot;product_style&quot;:&quot;v1&quot;,&quot;attr_row&quot;:&quot;class=\&quot;product-tabs row grid products\&quot; data-xlgdesktop=\&quot;4\&quot; data-desktop=\&quot;4\&quot; data-desktopsmall=\&quot;4\&quot; data-tablet=\&quot;3\&quot; data-landscape=\&quot;2\&quot; data-mobile=\&quot;2\&quot;&quot;}">
                                                                    <li>
                                                                        <a href="javascript:void(0)" data-bs-toggle="pill"
                                                                            data-bs-target="#best_selling-VEIMf-c11dc16"
                                                                            data-value="best_selling" class="active">A
                                                                            GRANEL</a>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="tbay-addon-content tab-content woocommerce">
                                                                <div class="tab-pane active active-content current"
                                                                    id="best_selling-VEIMf-c11dc16">
                                                                    <div class="product-tabs row grid products product-tabs products"
                                                                        data-xlgdesktop="4" data-desktop="4"
                                                                        data-desktopsmall="4" data-tablet="3"
                                                                        data-landscape="2" data-mobile="2">

                                                                        @foreach ($granelProducts as $product)
                                                                            <div class="item">
                                                                                <div
                                                                                    class="products-grid product type-product post-{{ $product['id'] }} status-publish instock product_cat-a-granel has-post-thumbnail sale taxable shipping-taxable purchasable product-type-simple">
                                                                                    <div class="product-block grid product v1"
                                                                                        data-product-id="{{ $product['id'] }}">
                                                                                        <div class="product-content">
                                                                                            <div class="block-inner">
                                                                                                <figure class="image ">
                                                                                                    <a title="{{ html_entity_decode($product['title'], ENT_QUOTES, 'UTF-8') }}"
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}"
                                                                                                        class="product-image">
                                                                                                        <img loading="lazy"
                                                                                                            decoding="async"
                                                                                                            width="480"
                                                                                                            height="480"
                                                                                                            src="{{ asset($product['images'][0]) }}"
                                                                                                            class="@if(empty($product['hover_image'])) image-no-effect @else image-effect attachment-shop_catalog @endif"
                                                                                                            alt="{{ $product['title'] }}" />
                                                                                                        @if ($product['hover_image'])
                                                                                                            <img loading="lazy"
                                                                                                                decoding="async"
                                                                                                                width="480"
                                                                                                                height="480"
                                                                                                                src="{{ assset($product['hover_image']) }}"
                                                                                                                class="image-hover"
                                                                                                                alt="" />
                                                                                                        @endif
                                                                                                    </a>
                                                                                                </figure>

                                                                                                <div class="group-buttons">
                                                                                                    <div class="add-cart"
                                                                                                        title="Añadir">
                                                                                                        <a href="javascript:void(0);"
                                                                                                            data-product-id="{{ $product['id'] ?? '' }}"
                                                                                                            class="wp-block-button__link add_to_cart_button ajax_add_to_cart"
                                                                                                            aria-label="Añadir al carrito: &ldquo;{{ $product['title'] ?? 'Producto' }}&rdquo;">
                                                                                                            <span
                                                                                                                class="title-cart">Añadir</span>
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-bag-2"></i>
                                                                                                        </a>
                                                                                                        <span
                                                                                                            id="woocommerce_loop_add_to_cart_link_describedby_{{ $product['id'] }}"
                                                                                                            class="screen-reader-text"></span>
                                                                                                    </div>
                                                                                                    <div class="button-wishlist shown-mobile"
                                                                                                        title="Lista de deseos">
                                                                                                        <div class="yith-add-to-wishlist-button-block yith-add-to-wishlist-button-block--initialized"
                                                                                                            data-attributes="{&quot;kind&quot;:&quot;button&quot;}">
                                                                                                            <a class="yith-wcwl-add-to-wishlist-button yith-wcwl-add-to-wishlist-button--anchor wishlist-button
            {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'wishlist-added' : '' }}"
                                                                                                                aria-label="Add To Wishlist: &ldquo;{{ $product['title'] }}&rdquo;"
                                                                                                                data-product-id="{{ $product['id'] }}"
                                                                                                                data-product-title="{{ $product['title'] }}"
                                                                                                                data-product-price="{{ $product['price'] }}"
                                                                                                                data-product-image="{{ asset($product['images'][0]) }}"
                                                                                                                data-product-slug="{{ $product['slug'] }}"
                                                                                                                href="#">
                                                                                                                <svg class="yith-wcwl-icon yith-wcwl-icon-svg yith-wcwl-add-to-wishlist-button-icon"
                                                                                                                    id="yith-wcwl-icon-heart-outline"
                                                                                                                    fill="{{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'red' : 'none' }}"
                                                                                                                    stroke-width="1.5"
                                                                                                                    stroke="currentColor"
                                                                                                                    viewBox="0 0 24 24"
                                                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                                                    <path
                                                                                                                        stroke-linecap="round"
                                                                                                                        stroke-linejoin="round"
                                                                                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z">
                                                                                                                    </path>
                                                                                                                </svg>
                                                                                                                <span
                                                                                                                    class="yith-wcwl-add-to-wishlist-button__label">
                                                                                                                    {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'En la lista' : 'Añadir a favoritos' }}
                                                                                                                </span>
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="tbay-quick-view">
                                                                                                        <a href="#"
                                                                                                            class="qview-button"
                                                                                                            title="Vista rápida"
                                                                                                            data-effect="mfp-move-from-top"
                                                                                                            data-product-id="{{ $product['id'] }}">
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-eye"></i>
                                                                                                            <span>Vista
                                                                                                                rápida</span>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                            <span class="onsale"><span
                                                                                                    class="saled">Oferta</span></span>
                                                                                            @endif

                                                                                            <div class="caption">
                                                                                                <span class="price">
                                                                                                    @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                                    <del
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['old_price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </del>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio original era:
                                                                                                        {{ \App\Support\Money::format($product['old_price']) }}&nbsp;&euro;.</span>
                                                                                                    @endif
                                                                                                    <ins
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </ins>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio actual es:
                                                                                                        {{ \App\Support\Money::format($product['price']) }}&nbsp;&euro;.</span>
                                                                                                    <small
                                                                                                        class="woocommerce-price-suffix">IVA
                                                                                                        incluido</small>
                                                                                                </span>

                                                                                                <h3 class="name">
                                                                                                    <a
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}">{{ html_entity_decode($product['title'], ENT_QUOTES, 'UTF-8') }}</a>
                                                                                                </h3>

                                                                                                <div class="group-content">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="elementor-element elementor-element-e19d51d elementor-align-center elementor-widget elementor-widget-button"
                                                    data-id="e19d51d" data-element_type="widget"
                                                    data-widget_type="button.default">
                                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                                        href="{{ route('category', ['category' => 'a-granel']) }}">
                                                        <span class="elementor-button-content-wrapper">
                                                            <span class="elementor-button-text">Ver todo</span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>


                                <section
                                    class="elementor-section elementor-top-section elementor-element elementor-element-8a52b07 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
                                    data-id="8a52b07" data-element_type="section"
                                    data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                    <div class="elementor-container elementor-column-gap-default">
                                        <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-06d766a"
                                            data-id="06d766a" data-element_type="column">
                                            <div class="elementor-widget-wrap elementor-element-populated">
                                                <div class="elementor-element elementor-element-119a993 elementor-product-v1 heading-tab-style-block elementor-widget elementor-widget-tbay-product-tabs"
                                                    data-id="119a993" data-element_type="widget"
                                                    data-widget_type="tbay-product-tabs.default">
                                                    <div class="elementor-widget-container">

                                                        <div class="tbay-element tbay-element-product-tabs ajax-active">

                                                            <div class="wrapper-heading-tab">
                                                                <ul class="product-tabs-title tabs-list nav nav-tabs"
                                                                    data-atts="{&quot;categories&quot;:[&quot;lena&quot;],&quot;cat_operator&quot;:&quot;IN&quot;,&quot;limit&quot;:8,&quot;orderby&quot;:&quot;date&quot;,&quot;order&quot;:&quot;asc&quot;,&quot;product_style&quot;:&quot;v1&quot;,&quot;attr_row&quot;:&quot;class=\&quot;product-tabs row grid products\&quot; data-xlgdesktop=\&quot;4\&quot; data-desktop=\&quot;4\&quot; data-desktopsmall=\&quot;4\&quot; data-tablet=\&quot;3\&quot; data-landscape=\&quot;2\&quot; data-mobile=\&quot;2\&quot;&quot;}">
                                                                    <li>
                                                                        <a href="javascript:void(0)" data-bs-toggle="pill"
                                                                            data-bs-target="#best_selling-2Rht9-c11dc16"
                                                                            data-value="best_selling"
                                                                            class="active">LEÑA
                                                                            PARA CHIMENEA</a>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="tbay-addon-content tab-content woocommerce">
                                                                <div class="tab-pane active active-content current"
                                                                    id="best_selling-2Rht9-c11dc16">
                                                                    <div class="product-tabs row grid products product-tabs products"
                                                                        data-xlgdesktop="4" data-desktop="4"
                                                                        data-desktopsmall="4" data-tablet="3"
                                                                        data-landscape="2" data-mobile="2">

                                                                        @foreach ($madeiraFogoProducts as $product)
                                                                            <div class="item">
                                                                                <div
                                                                                    class="products-grid product type-product post-{{ $product['id'] }} status-publish instock product_cat-lena has-post-thumbnail sale taxable shipping-taxable purchasable product-type-simple">
                                                                                    <div class="product-block grid product v1"
                                                                                        data-product-id="{{ $product['id'] }}">
                                                                                        <div class="product-content">
                                                                                            <div class="block-inner">
                                                                                                <figure class="image ">
                                                                                                    <a title="{{ html_entity_decode($product['title'], ENT_QUOTES, 'UTF-8') }}"
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}"
                                                                                                        class="product-image">
                                                                                                        <img loading="lazy"
                                                                                                            decoding="async"
                                                                                                            width="480"
                                                                                                            height="480"
                                                                                                            src="{{ asset($product['images'][0]) }}"
                                                                                                            class="@if(empty($product['hover_image'])) image-no-effect @else image-effect attachment-shop_catalog @endif"
                                                                                                            alt="{{ $product['title'] }}" />
                                                                                                        @if ($product['hover_image'])
                                                                                                            <img loading="lazy"
                                                                                                                decoding="async"
                                                                                                                width="480"
                                                                                                                height="480"
                                                                                                                src="{{ asset($product['hover_image']) }}"
                                                                                                                class="image-hover"
                                                                                                                alt="" />
                                                                                                        @endif
                                                                                                    </a>
                                                                                                </figure>

                                                                                                <div class="group-buttons">
                                                                                                    <div class="add-cart"
                                                                                                        title="Añadir">
                                                                                                        <a href="javascript:void(0);"
                                                                                                            data-product-id="{{ $product['id'] ?? '' }}"
                                                                                                            class="wp-block-button__link add_to_cart_button ajax_add_to_cart"
                                                                                                            aria-label="Añadir al carrito: &ldquo;{{ $product['title'] ?? 'Producto' }}&rdquo;">
                                                                                                            <span
                                                                                                                class="title-cart">Añadir</span>
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-bag-2"></i>
                                                                                                        </a>
                                                                                                        <span
                                                                                                            id="woocommerce_loop_add_to_cart_link_describedby_{{ $product['id'] }}"
                                                                                                            class="screen-reader-text"></span>
                                                                                                    </div>
                                                                                                    <div class="button-wishlist shown-mobile"
                                                                                                        title="Lista de deseos">
                                                                                                        <div class="yith-add-to-wishlist-button-block yith-add-to-wishlist-button-block--initialized"
                                                                                                            data-attributes="{&quot;kind&quot;:&quot;button&quot;}">
                                                                                                            <a class="yith-wcwl-add-to-wishlist-button yith-wcwl-add-to-wishlist-button--anchor wishlist-button
            {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'wishlist-added' : '' }}"
                                                                                                                aria-label="Add To Wishlist: &ldquo;{{ $product['title'] }}&rdquo;"
                                                                                                                data-product-id="{{ $product['id'] }}"
                                                                                                                data-product-title="{{ $product['title'] }}"
                                                                                                                data-product-price="{{ $product['price'] }}"
                                                                                                                data-product-image="{{ asset($product['images'][0]) }}"
                                                                                                                data-product-slug="{{ $product['slug'] }}"
                                                                                                                href="#">
                                                                                                                <svg class="yith-wcwl-icon yith-wcwl-icon-svg yith-wcwl-add-to-wishlist-button-icon"
                                                                                                                    id="yith-wcwl-icon-heart-outline"
                                                                                                                    fill="{{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'red' : 'none' }}"
                                                                                                                    stroke-width="1.5"
                                                                                                                    stroke="currentColor"
                                                                                                                    viewBox="0 0 24 24"
                                                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                                                    <path
                                                                                                                        stroke-linecap="round"
                                                                                                                        stroke-linejoin="round"
                                                                                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z">
                                                                                                                    </path>
                                                                                                                </svg>
                                                                                                                <span
                                                                                                                    class="yith-wcwl-add-to-wishlist-button__label">
                                                                                                                    {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'En la lista' : 'Añadir a favoritos' }}
                                                                                                                </span>
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="tbay-quick-view">
                                                                                                        <a href="#"
                                                                                                            class="qview-button"
                                                                                                            title="Vista rápida"
                                                                                                            data-effect="mfp-move-from-top"
                                                                                                            data-product-id="{{ $product['id'] }}">
                                                                                                            <i
                                                                                                                class="tb-icon tb-icon-eye"></i>
                                                                                                            <span>Vista
                                                                                                                rápida</span>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                            <span class="onsale"><span
                                                                                                    class="saled">Oferta</span></span>
                                                                                            @endif

                                                                                            <div class="caption">
                                                                                                <span class="price">
                                                                                                    @if(config('merchant.reference_prices_verified') && !empty($product['old_price']) && (float) $product['old_price'] > (float) $product['price'])
                                                                                                    <del
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['old_price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </del>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio original era:
                                                                                                        {{ \App\Support\Money::format($product['old_price']) }}&nbsp;&euro;.</span>
                                                                                                    @endif
                                                                                                    <ins
                                                                                                        aria-hidden="true">
                                                                                                        <span
                                                                                                            class="woocommerce-Price-amount amount">
                                                                                                            <bdi>{{ \App\Support\Money::format($product['price']) }}&nbsp;<span
                                                                                                                    class="woocommerce-Price-currencySymbol">&euro;</span></bdi>
                                                                                                        </span>
                                                                                                    </ins>
                                                                                                    <span
                                                                                                        class="screen-reader-text">El
                                                                                                        precio actual es:
                                                                                                        {{ \App\Support\Money::format($product['price']) }}&nbsp;&euro;.</span>
                                                                                                    <small
                                                                                                        class="woocommerce-price-suffix">IVA
                                                                                                        incluido</small>
                                                                                                </span>

                                                                                                <h3 class="name">
                                                                                                    <a
                                                                                                        href="{{ route('product.show', ['slug' => $product['slug']]) }}">{{ html_entity_decode($product['title'], ENT_QUOTES, 'UTF-8') }}</a>
                                                                                                </h3>

                                                                                                <div class="group-content">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="elementor-element elementor-element-deef3b9 elementor-align-center elementor-widget elementor-widget-button"
                                                    data-id="deef3b9" data-element_type="widget"
                                                    data-widget_type="button.default">
                                                    <a class="elementor-button elementor-button-link elementor-size-sm"
                                                        href="{{ route('category', ['category' => 'lena']) }}">
                                                        <span class="elementor-button-content-wrapper">
                                                            <span class="elementor-button-text">Ver todo</span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>


                                @if (isset($estufasPelletsProducts) && count($estufasPelletsProducts) > 0)
                                    <section class="lv-home-estufas">
                                        <div class="container">
                                            <div class="lv-home-estufas__head">
                                                <span class="lv-home-estufas__subtitle">Calefacción eficiente</span>
                                                <h2 class="lv-home-estufas__title">Estufas de pellets</h2>
                                            </div>
                                            <div class="lv-product-grid">
                                                @foreach ($estufasPelletsProducts as $estufa)
                                                    <x-product-card :product="$estufa" />
                                                @endforeach
                                            </div>
                                            <div class="lv-home-estufas__cta">
                                                <a class="lv-btn lv-btn--primary"
                                                    href="{{ route('category', ['category' => 'estufas-de-pellets']) }}">Ver
                                                    todas las estufas de pellets</a>
                                            </div>
                                        </div>
                                    </section>
                                @endif


                                {{-- Avis clients masqués à la demande du client --}}
                                {{-- @include('section.avant-footer') --}}
                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </div>

    </div>

    <section class="lv-seo-home">
        <div class="container">
            <h1 class="lv-seo-home__h1">Leña en El Molar — Leñas El Molar C.B.</h1>
            <p class="lv-seo-home__lead">
                En <strong>Leñas El Molar</strong> (también <strong>Leñas y Carbones El Molar</strong>,
                forma jurídica <strong>Leñas El Molar C.B.</strong>) presentamos la venta de
                <strong>leña de calefacción</strong>, <strong>leña de encina</strong>,
                leña para chimenea y estufa, leña para barbacoa, venta al por mayor,
                distribución de leña y <strong>carbón</strong>.
                Actividad indicada desde 2010. Domicilio en Calle de la Salud, 4, 28710 El Molar (Madrid).
            </p>

            <h2>Leña para chimenea, estufa y calefacción</h2>
            <p>
                Ofrecemos leña orientada a chimeneas, estufas y sistemas de calefacción.
                Las características concretas de cada producto (formato, precio, disponibilidad)
                se indican solo cuando están confirmadas en la ficha correspondiente.
            </p>

            <h2>Venta al por mayor y distribución</h2>
            <p>
                La venta al por mayor y la distribución / entrega local están referenciadas.
                Condiciones, zonas y formatos se confirman bajo petición; no publicamos
                capacidades, MOQ ni tarifas no verificadas.
            </p>

            <h2>Contacto</h2>
            <p>
                Teléfono: +34 679 24 55 97.
                <a href="{{ route('contacto') }}">Formulario de contacto</a>.
                <a href="{{ route('sobre-nos') }}">Más información sobre la empresa</a>.
            </p>

            <h2>Preguntas frecuentes</h2>
            <div class="lv-seo-home__faq">
                <details>
                    <summary>¿Dónde está Leñas El Molar?</summary>
                    <p>Calle de la Salud, 4, 28710 El Molar (Madrid), España. CIF E85899003.</p>
                </details>
                <details>
                    <summary>¿Qué productos ofrece?</summary>
                    <p>Leña de calefacción, leña de encina, leña para estufa y chimenea, leña para barbacoa, distribución de leña y carbón. También se referencia la venta al por mayor.</p>
                </details>
                <details>
                    <summary>¿Hacéis entrega?</summary>
                    <p>Existe una actividad de distribución / entrega local. Consulta cobertura y condiciones por teléfono o mediante el formulario de contacto antes de pedir.</p>
                </details>
                <details>
                    <summary>¿Cuál es el CNAE?</summary>
                    <p>CNAE indicado: 161 — Aserrado y cepillado de la madera. Es una referencia administrativa y no implica una afirmación sobre capacidad de producción.</p>
                </details>
            </div>
        </div>
    </section>

    @include('layouts.partials.footer.public')

@endsection

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect([
        ['¿Dónde está Leñas El Molar?', 'Calle de la Salud, 4, 28710 El Molar (Madrid), España. CIF E85899003.'],
        ['¿Qué productos ofrece?', 'Leña de calefacción, leña de encina, leña para estufa y chimenea, leña para barbacoa, distribución de leña y carbón. También se referencia la venta al por mayor.'],
        ['¿Hacéis entrega?', 'Existe una actividad de distribución / entrega local. Consulta cobertura y condiciones por teléfono o mediante el formulario de contacto antes de pedir.'],
        ['¿Cuál es el CNAE?', 'CNAE indicado: 161 — Aserrado y cepillado de la madera. Es una referencia administrativa y no implica una afirmación sobre capacidad de producción.'],
    ])->map(fn ($q) => [
        '@type' => 'Question',
        'name' => $q[0],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $q[1]],
    ])->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@push('styles')
<style>
    .lv-seo-home { padding: 48px 0; background: #fafafa; border-top: 1px solid #ececec; }
    .lv-seo-home .container { max-width: 1000px; margin: 0 auto; padding: 0 20px; }
    .lv-seo-home__h1 { font-size: 1.9rem; line-height: 1.25; margin: 0 0 16px; }
    .lv-seo-home__lead { font-size: 1.05rem; }
    .lv-seo-home h2 { font-size: 1.3rem; margin: 32px 0 10px; }
    .lv-seo-home p, .lv-seo-home li { line-height: 1.7; color: #333; }
    .lv-seo-home a { color: #F55F1E; text-decoration: underline; }
    .lv-seo-home__faq details { border-bottom: 1px solid #e2e2e2; padding: 12px 0; }
    .lv-seo-home__faq summary { cursor: pointer; font-weight: 600; }
    .lv-seo-home__faq details p { margin: 10px 0 0; }
    @media (max-width: 600px) { .lv-seo-home__h1 { font-size: 1.5rem; } }
</style>
@endpush

@push('scripts')
    @include('section.modeldetail');
@endpush
