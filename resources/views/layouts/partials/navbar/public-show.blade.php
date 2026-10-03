<div class="lv-navbar__topbar">
    <div class="lv-container lv-navbar__topbar-inner">
        <span class="lv-navbar__topbar-item">Consulta disponibilidad de entrega</span>
        <a href="tel:+34679245597" class="lv-navbar__topbar-item lv-navbar__topbar-link">📞 +34 679 24 55 97</a>
    </div>
</div>

<div id="tbay-mobile-smartmenu" data-title="Menu" class="tbay-mmenu d-xl-none">


    <div class="tbay-offcanvas-body">

        <div id="mmenu-close">
            <button type="button" class="btn btn-toggle-canvas" data-toggle="offcanvas">
                <i class="tb-icon tb-icon-close-01"></i>
            </button>
        </div>

        <nav id="tbay-mobile-menu-navbar" class="menu navbar navbar-offcanvas navbar-static"
            data-id="menu-categories-menu-icon">
            <div id="main-mobile-menu-mmenu" class="menu-categories-menu-icon-container">
                <ul id="main-mobile-menu-mmenu-wrapper" class="menu" data-id="categories-menu-icon">
                    @if (isset($categories) && count($categories) > 0)
                        @foreach ($categories as $categorySlug => $categoryName)
                            <li class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                                <a class="elementor-item" href="{{ route('category', ['category' => $categorySlug]) }}">
                                    <span class="menu-title">{{ $categoryName }}</span>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <!-- Fallback si pas de catégories trouvées -->
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                            <a class="elementor-item"
                                href="{{ route('category', ['category' => 'pellets-de-madera']) }}">
                                <span class="menu-title">Pellets de madera</span>
                            </a>
                        </li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                            <a class="elementor-item" href="{{ route('category', ['category' => 'cocinas-de-lena']) }}">
                                <span class="menu-title">Cocinas de leña</span>
                            </a>
                        </li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                            <a class="elementor-item" href="{{ route('category', ['category' => 'cocinas-de-lena']) }}">
                                <span class="menu-title">Cocinas de leña</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

    </div>
    <div id="mm-tbay-bottom">

        <div class="mm-bottom-track-wrapper">

            <div class="mm-bottom-langue-currency ">
                <div class="mm-bottom-langue">
                </div>

            </div>
        </div>


    </div>

</div>
<div class="topbar-device-mobile d-xl-none clearfix ">

    <div class="active-mobile"><a href="javascript:void(0);" class="btn btn-sm mmenu-open"><i
                class="tb-icon tb-icon-menu"></i></a><a href="#page" class="btn btn-sm"><i
                class="tb-icon tb-icon-cross"></i></a></div>
    <div class="mobile-logo"><a href="{{ route('home') }}"><img fetchpriority="high"
                src="{{ asset('wp-content/uploads/2022/01/er-01-scaled.png') }}" width="48" height="48"
                alt="Leñas El Molar C.B."></a></div>
    <div class="device-mini_cart top-cart tbay-element-mini-cart">
        <div class="tbay-offcanvas-cart sidebar-right offcanvas offcanvas-end" id="cart-offcanvas-mobile">
            <div class="offcanvas-header widget-header-cart">
                <div class="header-cart-content">
                    <h3 class="widget-title heading-title">Carrito de compra</h3>
                    <a href="javascript:" class="offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
                            class="tb-icon tb-icon-cross"></i></a>
                </div>
            </div>
            <div class="offcanvas-body widget_shopping_cart_content">
                <div class="mini_cart_content">
                    <div class="mini_cart_inner">
                        <div class="mcart-border">
                            <ul class="cart_empty ">
                                <li><span>Tu carrito está vacío</span></li>
                                <li class="total"><a class="button wc-continue" href="{{ route('home') }}">Seguir
                                        comprando<i class="tb-icon tb-icon-angle-right"></i></a></li>
                            </ul>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tbay-topcart">
            <div id="cart-SCe7K" class="cart-dropdown dropdown">
                <a class="dropdown-toggle mini-cart v2" data-bs-toggle="offcanvas"
                    data-bs-target="#cart-offcanvas-mobile" aria-controls="cart-offcanvas-mobile"
                    href="javascript:void(0);">
                    <i class="tb-icon tb-icon-cart"></i>
                    <span class="mini-cart-items">
                        0 </span>
                    <span>Carrito</span>
                </a>
            </div>
        </div>
    </div>
</div>


<div class="footer-device-mobile d-xl-none clearfix">
    <div class="list-menu-icon">
        <div class="menu-icon">
            <a title="Inicio" class="home {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                <span class="menu-icon-child">
                    <i class="tb-icon tb-icon-home3"></i>
                    <span>Inicio</span>
                </span>
            </a>
        </div>

        <div class="menu-icon">
            <a title="Tienda" class="shop {{ request()->routeIs('loja') ? 'active' : '' }}"
                href="{{ route('loja') }}">
                <span class="menu-icon-child">
                    <i class="tb-icon tb-icon-store"></i>
                    <span>Tienda</span>
                </span>
            </a>
        </div>

        <div class="menu-icon">
            <a title="Finalizar compra" class="checkout {{ request()->routeIs('carrinho') ? 'active' : '' }}"
                href="{{ route('carrinho') }}">
                <span class="menu-icon-child">
                    <i class="icon- icon-credit-card"></i>
                    <span>Finalizar compra</span>
                </span>
            </a>
        </div>

        <div class="menu-icon">
            <a title="Lista de deseos" class="wishlist {{ request()->routeIs('wishlist.*') ? 'active' : '' }}"
                href="{{ route('wishlist.index') }}">
                <span class="menu-icon-child">
                    <i class="icon- icon-heart"></i>
                    <span class="count count_wishlist"><span>0</span></span>
                    <span>Lista de deseos</span>
                </span>
            </a>
        </div>

        <div class="menu-icon">
            <a title="Contacto" class="account {{ request()->routeIs('contacto') ? 'active' : '' }}"
                href="{{ route('contacto') }}">
                <span class="menu-icon-child">
                    <i class="tb-icon tb-icon-account"></i>
                    <span>Contacto</span>
                </span>
            </a>
        </div>
    </div>
</div>


<header id="tbay-header" class="tbay_header-template site-header ">


    <style>
        .elementor-939 .elementor-element.elementor-element-ab3c07f>.elementor-container>.elementor-column>.elementor-widget-wrap {
            align-content: center;
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-ab3c07f {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            padding: 23px 0px 25px 0px;
        }

        .elementor-939 .elementor-element.elementor-element-ab3c07f>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-939 .elementor-element.elementor-element-08d7568>div.elementor-element-populated {
            padding: 0px 10px 0px 20px !important;
        }

        .elementor-939 .elementor-element.elementor-element-8314a1b .toggle-menu-title span {
            line-height: 52px;
        }

        .elementor-939 .elementor-element.elementor-element-8314a1b .toggle-menu-title,
        .elementor-939 .elementor-element.elementor-element-8314a1b .toggle-menu-title>* {
            color: #FFFFFF;
        }

        .elementor-939 .elementor-element.elementor-element-8314a1b .toggle-menu-title {
            background-color: #9E5033;
        }

        .elementor-939 .elementor-element.elementor-element-78a3c28>div.elementor-element-populated {
            padding: 0px 60px 0px 0px !important;
        }

        .elementor-939 .elementor-element.elementor-element-6ed3584 {
            width: var(--container-widget-width, 68.373%);
            max-width: 68.373%;
            --container-widget-width: 68.373%;
            --container-widget-flex-grow: 0;
        }

        .elementor-939 .elementor-element.elementor-element-6ed3584.elementor-element {
            --flex-grow: 0;
            --flex-shrink: 0;
        }

        .elementor-939 .elementor-element.elementor-element-6ed3584 .tbay-search-form .form-group .input-group {
            padding: 9px 0px 9px 0px;
            border-style: solid;
            border-width: 1px 1px 1px 1px;
            border-color: #D7D7D7;
        }

        .elementor-939 .elementor-element.elementor-element-6ed3584 .SumoSelect.open>.optWrapper,
        .elementor-939 .elementor-element.elementor-element-6ed3584 .autocomplete-suggestions {
            margin-top: 1px;
        }

        .elementor-939 .elementor-element.elementor-element-d3b67e6:not(.elementor-motion-effects-element-type-background)>.elementor-widget-wrap,
        .elementor-939 .elementor-element.elementor-element-d3b67e6>.elementor-widget-wrap>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #F8F8F8;
        }

        .elementor-939 .elementor-element.elementor-element-d3b67e6.elementor-column>.elementor-widget-wrap {
            justify-content: center;
        }

        .elementor-939 .elementor-element.elementor-element-d3b67e6>.elementor-element-populated {
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
            margin: 0px 20px 0px 0px;
            --e-column-margin-right: 20px;
            --e-column-margin-left: 0px;
        }

        .elementor-939 .elementor-element.elementor-element-d3b67e6>.elementor-element-populated>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-939 .elementor-element.elementor-element-d3b67e6>div.elementor-element-populated {
            padding: 0px 18px 0px 0px !important;
        }

        .elementor-939 .elementor-element.elementor-element-d38df21>.elementor-widget-container {
            margin: 0px 0px 0px -6px;
        }

        .elementor-939 .elementor-element.elementor-element-d38df21 .tbay-login a i {
            font-size: 21px !important;
        }

        .elementor-939 .elementor-element.elementor-element-37426c5>.elementor-widget-container {
            margin: 0px 0px 0px 27px;
        }

        .elementor-939 .elementor-element.elementor-element-37426c5 .cart-icon span.mini-cart-items {
            font-size: 13px;
            font-weight: 400;
            background: #E70025;
        }

        .elementor-939 .elementor-element.elementor-element-37426c5 .cart-popup .dropdown-menu.show {
            inset: 54px 1px auto auto !important;
        }

        .rtl .elementor-939 .elementor-element.elementor-element-37426c5 .cart-popup .dropdown-menu.show {
            inset: 54px auto auto 0px !important;
        }

        .elementor-939 .elementor-element.elementor-element-82fbfa0>.elementor-container>.elementor-column>.elementor-widget-wrap {
            align-content: center;
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-82fbfa0:not(.elementor-motion-effects-element-type-background),
        .elementor-939 .elementor-element.elementor-element-82fbfa0>.elementor-motion-effects-container>.elementor-motion-effects-layer {
            background-color: #FAFAFA;
        }

        .elementor-939 .elementor-element.elementor-element-82fbfa0 {
            box-shadow: 0px 1px 1px 0px #EEEEEE;
            transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
        }

        .elementor-939 .elementor-element.elementor-element-82fbfa0>.elementor-background-overlay {
            transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
        }

        .elementor-bc-flex-widget .elementor-939 .elementor-element.elementor-element-c26a08a.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-c26a08a.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main .elementor-item {
            padding: 19px 0px 19px 0px;
        }

        .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main .dropdown-menu .elementor-item {
            padding: 0;
        }

        .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main>.megamenu>li:first-child>.elementor-item,
        .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main>.megamenu>li:first-child>.elementor-item+.sub-menu {
            margin-left: 0;
            left: 0;
        }

        .rtl .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main>.megamenu>li:first-child>.elementor-item,
        .rtl .elementor-939 .elementor-element.elementor-element-993930a .elementor-nav-menu--main>.megamenu>li:first-child>.elementor-item+.sub-menu {
            margin-right: 0;
            right: 0;
        }

        .elementor-bc-flex-widget .elementor-939 .elementor-element.elementor-element-faa6dd4.elementor-column .elementor-widget-wrap {
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-faa6dd4.elementor-column.elementor-element[data-element_type="column"]>.elementor-widget-wrap.elementor-element-populated {
            align-content: center;
            align-items: center;
        }

        .elementor-939 .elementor-element.elementor-element-faa6dd4.elementor-column>.elementor-widget-wrap {
            justify-content: flex-end;
        }

        .elementor-939 .elementor-element.elementor-element-ce2e2b5 .content-empty {
            text-align: center;
        }

        .elementor-939 .elementor-element.elementor-element-ce2e2b5 .product-recently-viewed-header h3 {
            font-size: 15px;
            font-weight: 400;
            line-height: 66px;
            color: #191919;
        }

        .elementor-939 .elementor-element.elementor-element-ce2e2b5 .product-recently-viewed-header:hover h3,
        .elementor-939 .elementor-element.elementor-element-ce2e2b5 .product-recently-viewed-header:hover h3:after {
            color: #F55F1E;
        }

        @media (min-width: 768px) {
            .elementor-939 .elementor-element.elementor-element-08d7568 {
                width: 14.026%;
            }

            .elementor-939 .elementor-element.elementor-element-519d620 {
                width: 17.986%;
            }

            .elementor-939 .elementor-element.elementor-element-78a3c28 {
                width: 50.278%;
            }

            .elementor-939 .elementor-element.elementor-element-d3b67e6 {
                width: 17.675%;
            }

            .elementor-939 .elementor-element.elementor-element-c26a08a {
                width: 59.653%;
            }

            .elementor-939 .elementor-element.elementor-element-faa6dd4 {
                width: 40.313%;
            }
        }
    </style>
    <div data-elementor-type="wp-post" data-elementor-id="939" class="elementor elementor-939">
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-ab3c07f elementor-section-content-middle elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="ab3c07f" data-element_type="section"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-08d7568"
                    data-id="08d7568" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-3bfdaf5 elementor-widget elementor-widget-maia-site-logo w-auto elementor-widget-tbay-base"
                            data-id="3bfdaf5" data-element_type="widget" data-widget_type="maia-site-logo.default">

                            <div class="tbay-element tbay-element-site-logo">

                                <div class="header-logo">

                                    <a href="{{ route('home') }}">
                                        <img width="56" height="56"
                                            src="{{ asset('wp-content/uploads/2022/01/er-01-scaled.png') }}"
                                            class="header-logo-img" alt="Leñas El Molar C.B." decoding="async" /> </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-519d620"
                    data-id="519d620" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-8314a1b elementor-toggle-content-menu-yes elementor-widget elementor-widget-tbay-nav-menu"
                            data-id="8314a1b" data-element_type="widget"
                            data-settings="{&quot;layout&quot;:&quot;vertical&quot;,&quot;type_menu&quot;:&quot;toggle&quot;}"
                            data-widget_type="tbay-nav-menu.default">
                            <div class="elementor-widget-container">
                                <div class="tbay-element tbay-element-nav-menu category-inside"
                                    data-wrapper="{&quot;layout&quot;:&quot;vertical&quot;,&quot;type_menu&quot;:&quot;toggle&quot;}">
                                    <h3 class="toggle-menu-title category-inside-title"><a href="javascript:void(0);"
                                            class="click-show-menu menu-click"><i
                                                class="tb-icon tb-icon-justifyleft"></i><span>Categorías</span></a>
                                    </h3>

                                    <div class="category-inside-content">
                                        <nav class="elementor-nav-menu--main elementor-nav-menu__container elementor-nav-menu--layout-vertical tbay-vertical tbay-treevertical-lv1 vertical-submenu-right"
                                            data-id="categories-menu-icon">
                                            <ul id="menu-1-tyy9y"
                                                class="elementor-nav-menu menu nav navbar-nav megamenu flex-column">

                                                @if (isset($categories) && count($categories) > 0)
                                                    @foreach ($categories as $categorySlug => $categoryName)
                                                        <li
                                                            class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                                                            <a class="elementor-item"
                                                                href="{{ route('category', ['category' => $categorySlug]) }}">
                                                                <span class="menu-title">{{ $categoryName }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <!-- Fallback si pas de catégories trouvées -->
                                                    <li
                                                        class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                                                        <a class="elementor-item"
                                                            href="{{ route('category', ['category' => 'pellets-de-madera']) }}">
                                                            <span class="menu-title">Pellets de madera</span>
                                                        </a>
                                                    </li>
                                                    <li
                                                        class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                                                        <a class="elementor-item"
                                                            href="{{ route('category', ['category' => 'cocinas-de-lena']) }}">
                                                            <span class="menu-title">Cocinas de leña</span>
                                                        </a>
                                                    </li>
                                                    <li
                                                        class="menu-item menu-item-type-taxonomy menu-item-object-product_cat">
                                                        <a class="elementor-item"
                                                            href="{{ route('category', ['category' => 'cocinas-de-lena']) }}">
                                                            <span class="menu-title">Cocinas de leña</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-78a3c28"
                    data-id="78a3c28" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-6ed3584 elementor-widget__width-initial elementor-widget elementor-widget-tbay-search-form"
                            data-id="6ed3584" data-element_type="widget" data-widget_type="tbay-search-form.default">
                            <div class="elementor-widget-container">
                                <div class="tbay-element tbay-element-search-form">
                                    <div class="tbay-search-form">
                                        @php
                                            // Récupérer les catégories avec leur nombre de produits
                                            $categories = [];

                                            if (config()->has('loja_products')) {
                                                $allProducts = collect(config('loja_products'));

                                                // Compter les produits par catégorie
                                                $categoryCounts = $allProducts->groupBy('category')->map->count();

                                                // Créer le tableau des catégories avec leur nom formaté et leur compte
                                                $categories = $allProducts
                                                    ->pluck('category')
                                                    ->unique()
                                                    ->mapWithKeys(function ($categorySlug) use ($categoryCounts) {
                                                        $categoryName = strtoupper(
                                                            str_replace('-', ' ', $categorySlug),
                                                        );
                                                        $count = $categoryCounts[$categorySlug] ?? 0;

                                                        return [
                                                            $categorySlug => [
                                                                'name' => $categoryName,
                                                                'count' => $count,
                                                            ],
                                                        ];
                                                    })
                                                    ->sortBy('name')
                                                    ->toArray();
                                            }
                                        @endphp

                                        <form action="{{ route('loja') }}" method="get"
                                            class="maia-ajax-search searchform" data-thumbnail="1" data-subtitle="1"
                                            data-appendto=".search-results-bo7Cn" data-price="1" data-minChars="2"
                                            data-post-type="product" data-count="5">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="select-category input-group-addon">
                                                        <select name='product_cat' id='product-cat-bo7Cn'
                                                            class='dropdown_product_cat form-control'>
                                                            <option value=''
                                                                {{ !request('product_cat') ? 'selected' : '' }}>
                                                                Todas las categorías
                                                            </option>

                                                            @foreach ($categories as $categorySlug => $categoryData)
                                                                <option class="level-0" value="{{ $categorySlug }}"
                                                                    {{ request('product_cat') == $categorySlug ? 'selected' : '' }}>
                                                                    {{ $categoryData['name'] }}&nbsp;&nbsp;({{ $categoryData['count'] }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <input data-style="right" type="text"
                                                        placeholder="Buscar productos" name="s"
                                                        value="{{ request('s') }}" required minlength="2"
                                                        oninvalid="this.setCustomValidity('Introduce al menos 2 caracteres')"
                                                        oninput="this.setCustomValidity('')"
                                                        class="tbay-search form-control input-sm" />

                                                    <div class="search-results-wrapper">
                                                        <div class="maia-search-results search-results-bo7Cn"></div>
                                                    </div>

                                                    <div class="button-group input-group-addon">
                                                        <button type="submit" class="button-search btn btn-sm">
                                                            <i aria-hidden="true"
                                                                class="tb-icon tb-icon-search-normal"></i>
                                                        </button>
                                                        <div class="tbay-preloader"></div>
                                                    </div>

                                                    <input type="hidden" name="post_type" value="product"
                                                        class="post_type" />

                                                    <!-- Garder les autres paramètres de filtre s'ils existent -->
                                                    @if (request('min_price'))
                                                        <input type="hidden" name="min_price"
                                                            value="{{ request('min_price') }}">
                                                    @endif
                                                    @if (request('max_price'))
                                                        <input type="hidden" name="max_price"
                                                            value="{{ request('max_price') }}">
                                                    @endif
                                                    @if (request('stock'))
                                                        <input type="hidden" name="stock"
                                                            value="{{ request('stock') }}">
                                                    @endif
                                                    @if (request('orderby'))
                                                        <input type="hidden" name="orderby"
                                                            value="{{ request('orderby') }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-d3b67e6"
                    data-id="d3b67e6" data-element_type="column"
                    data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        {{--       <div
                            class="elementor-element elementor-element-d38df21 elementor-widget w-auto elementor-widget-tbay-account"
                            data-id="d38df21" data-element_type="widget"
                            data-widget_type="tbay-account.default">
                            <div class="elementor-widget-container">
                                <div class="tbay-element tbay-element-account header-icon">
                                    <div class="tbay-login">
                                        <a data-bs-toggle="modal" data-bs-target="#custom-login-wrapper"
                                           href="javascript:void(0)">
                                            <i aria-hidden="true" class="tb-icon tb-icon-user"></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="elementor-element elementor-element-37426c5 elementor-widget w-auto elementor-widget-tbay-mini-cart"
                            data-id="37426c5" data-element_type="widget" data-widget_type="tbay-mini-cart.default">
                            <div class="elementor-widget-container">
                                <div class="tbay-element tbay-element-mini-cart">
                                    <div class="tbay-topcart popup">
                                        <div id="cart-xsaAl" class="cart-dropdown cart-popup dropdown">
                                            <a class="dropdown-toggle mini-cart" data-bs-toggle="dropdown"
                                                data-bs-auto-close="outside" href="javascript:void(0);"
                                                title="Ver tu carrito de la compra">
                                                <span class="cart-icon">
                                                    <i class="tb-icon tb-icon-bag-happy"></i>
                                                    <span class="mini-cart-items">0</span>
                                                </span>
                                            </a>
                                            <div class="dropdown-menu">
                                                <div class="widget_shopping_cart_content" id="mini-cart-content">
                                                    <!-- Le contenu sera chargé dynamiquement par JavaScript -->
                                                    <div class="mini_cart_content">
                                                        <div class="mini_cart_inner">
                                                            <div class="text-center p-3">
                                                                <div class="spinner-border spinner-border-sm"
                                                                    role="status">
                                                                    <span class="visually-hidden">Cargando...</span>
                                                                </div>
                                                                <span class="ms-2">Cargando carrito...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
        <section
            class="elementor-section elementor-top-section elementor-element elementor-element-82fbfa0 elementor-section-stretched elementor-section-content-middle elementor-section-boxed elementor-section-height-default elementor-section-height-default"
            data-id="82fbfa0" data-element_type="section"
            data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="elementor-container elementor-column-gap-default">
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-c26a08a"
                    data-id="c26a08a" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-993930a elementor-widget elementor-widget-tbay-nav-menu"
                            data-id="993930a" data-element_type="widget"
                            data-settings="{&quot;layout&quot;:&quot;horizontal&quot;}"
                            data-widget_type="tbay-nav-menu.default">
                            <div class="elementor-widget-container">
                                <div class="tbay-element tbay-element-nav-menu"
                                    data-wrapper="{&quot;layout&quot;:&quot;horizontal&quot;,&quot;type_menu&quot;:null}">


                                    <nav class="elementor-nav-menu--main elementor-nav-menu__container elementor-nav-menu--layout-horizontal tbay-horizontal"
                                        data-id="main-menu">
                                        <ul id="menu-1-hnAZU"
                                            class="elementor-nav-menu menu nav navbar-nav megamenu flex-row">

                                            <li class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
                                                <a class="elementor-item" href="{{ route('home') }}">
                                                    <span class="menu-title">Inicio</span>
                                                </a>
                                            </li>

                                            <li class="menu-item {{ request()->routeIs('loja') ? 'active' : '' }}">
                                                <a class="elementor-item" href="{{ route('loja') }}">
                                                    <span class="menu-title">Tienda</span>
                                                </a>
                                            </li>

                                            <li
                                                class="menu-item {{ request()->routeIs('sobre-nos') ? 'active' : '' }}">
                                                <a class="elementor-item" href="{{ route('sobre-nos') }}">
                                                    <span class="menu-title">Sobre nosotros</span>
                                                </a>
                                            </li>

                                            <li
                                                class="menu-item {{ request()->routeIs('contacto') ? 'active' : '' }}">
                                                <a class="elementor-item" href="{{ route('contacto') }}">
                                                    <span class="menu-title">Contacto</span>
                                                </a>
                                            </li>

                                            @if (\App\Support\Certifications::all())
<li
                                                class="menu-item {{ request()->routeIs('certificaciones') ? 'active' : '' }}">
                                                <a class="elementor-item" href="{{ route('certificaciones') }}">
                                                    <span class="menu-title">Certificaciones</span>
                                                </a>
                                            </li>
                                            @endif

                                        </ul>

                                    </nav>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-faa6dd4"
                    data-id="faa6dd4" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-ce2e2b5 elementor-widget elementor-widget-tbay-product-recently-viewed w-auto tbay-carousel"
                            data-id="ce2e2b5" data-element_type="widget"
                            data-widget_type="tbay-product-recently-viewed.default">
                            <div class="elementor-widget-container">

                                <div class="tbay-element tbay-element-product-recently-viewed product-recently-viewed-header"
                                    data-wrapper="{&quot;layout&quot;:&quot;header&quot;}" data-column="11">


                                    {{-- Static "recently viewed" block from the WordPress template removed: it always showed the same product with a dead link. --}}


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <div id="nav-cover"></div>
    <div class="bg-close-canvas-menu"></div>
</header>
