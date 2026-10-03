@extends('layouts.app')

@section('title', $offer->title)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product['short_description'] ?? $product['title']), 155))
@section('canonical', $offer->link)
@section('og_image', $offer->imageLink ?: (!empty($product['images'][0]) ? asset($product['images'][0]) : ''))

@push('head')
<script type="application/ld+json">
{!! json_encode($offer->toJsonLd(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => url('/')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => \App\Support\CategoryLabels::label($product['category'] ?? null), 'item' => url('categoria/'.($product['category'] ?? ''))],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $offer->title, 'item' => $offer->link],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
    @include('layouts.partials.navbar.public-show')

    <div class="lv-product">
        <div class="lv-container">

            <div class="lv-product__breadcrumb">
                <a href="{{ route('home') }}">Inicio</a>
                &rsaquo;
                <a href="{{ route('category', ['category' => $product['category']]) }}">{{ \App\Support\CategoryLabels::label($product['category']) }}</a>
                @if($prevProduct || $nextProduct)
                    <span style="float:right;">
                        @if($prevProduct)
                            <a href="{{ route('product.show', $prevProduct['slug']) }}">&laquo; Anterior</a>
                        @endif
                        @if($prevProduct && $nextProduct) &nbsp;|&nbsp; @endif
                        @if($nextProduct)
                            <a href="{{ route('product.show', $nextProduct['slug']) }}">Siguiente &raquo;</a>
                        @endif
                    </span>
                @endif
            </div>

            <div class="lv-product__layout">

                {{-- Gallery --}}
                <div class="lv-gallery">
                    <div class="lv-gallery__main">
                        @if(count($product['images']) > 1)
                            <button type="button" class="lv-gallery__arrow lv-gallery__arrow--prev" aria-label="Imagen anterior">
                                <i class="tb-icon tb-icon-angle-left"></i>
                            </button>
                        @endif
                        <img id="lv-gallery-main-img" src="{{ $offer->imageLink ?: asset($product['images'][0] ?? '') }}" alt="{{ $offer->title }}" width="800" height="800">
                        @if(count($product['images']) > 1)
                            <button type="button" class="lv-gallery__arrow lv-gallery__arrow--next" aria-label="Imagen siguiente">
                                <i class="tb-icon tb-icon-angle-right"></i>
                            </button>
                        @endif
                    </div>
                    @if(count($product['images']) > 1)
                        <div class="lv-gallery__thumbs">
                            @foreach($product['images'] as $index => $image)
                                <button type="button" class="lv-gallery__thumb {{ $index === 0 ? 'is-active' : '' }}" data-src="{{ asset($image) }}">
                                    <img src="{{ asset($image) }}" alt="{{ $product['title'] }} - {{ $index + 1 }}">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Info panel --}}
                <div class="lv-product__info">
                    <h1 class="lv-product__title">{{ $offer->title }}</h1>

                    <div class="lv-product__price-row">
                        @if ($offer->salePrice)
                            <span class="lv-product__price-old">{{ \App\Support\Money::eur($product['old_price']) }}</span>
                        @endif
                        <span class="lv-product__price" data-offer-price="{{ number_format($offer->offerAmount, 2, '.', '') }}">{{ \App\Support\Money::eur($offer->offerAmount) }}</span>
                        <span class="lv-product__price-note">(IVA incluido)</span>
                    </div>
                    @php
                        $unitPriceLabel = \App\Support\UnitPricing::displayString(
                            \App\Support\Money::toFloat($product['price']),
                            isset($product['unit_measure_value']) ? (float) $product['unit_measure_value'] : null,
                            $product['unit_measure_unit'] ?? null
                        );
                    @endphp
                    @if ($unitPriceLabel)
                        <div class="lv-product__unit-price">{{ $unitPriceLabel }}</div>
                    @endif

                    <div class="lv-product__stock {{ $offer->inStock ? 'lv-product__stock--in' : 'lv-product__stock--out' }}" data-availability="{{ $offer->availability->value }}">
                        <i class="tb-icon {{ $offer->inStock ? 'tb-icon-check-circle' : 'tb-icon-close-01' }}"></i>
                        {{ $offer->inStock ? 'En stock' : 'Agotado' }}
                    </div>

                    <ul class="lv-product__benefits">
                        <li><i class="tb-icon tb-icon-check-circle"></i> {{ \App\Support\ShippingPolicy::shortLabel() }} — <a href="{{ route('politicaDeEntrega') }}">política de entrega</a></li>
                        <li><i class="tb-icon tb-icon-check-circle"></i> Pago por transferencia bancaria (pedido enviado tras recibir el pago)</li>
                        <li><i class="tb-icon tb-icon-check-circle"></i> Desistimiento en {{ $offer->returnDays }} días naturales (gastos de devolución a cargo del cliente) — <a href="{{ route('politicaDeReembolso') }}">condiciones</a></li>
                    </ul>

                    @php($lvCerts = \App\Support\Certifications::forProduct($product))
                    @if (!empty($lvCerts))
                        <div class="lv-product__certs">
                            @foreach ($lvCerts as $lvCert)
                                <a class="lv-cert-badge" href="{{ route('certificaciones') }}#{{ $lvCert['key'] }}"
                                    title="Ver certificación {{ $lvCert['name'] }}">
                                    <i class="tb-icon tb-icon-check-circle"></i> {{ $lvCert['name'] }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($product['short_description']))
                        <p class="lv-product__desc">{{ $product['short_description'] }}</p>
                    @endif

                    <form class="cart" action="{{ route('cart.add') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">

                        <div class="lv-product__actions">
                            <div class="lv-product__qty">
                                <label class="screen-reader-text" for="quantity_{{ $product['id'] }}">Cantidad de {{ $product['title'] }}</label>
                                <div class="quantity-selector" style="display:flex; align-items:stretch; width:100%;">
                                    <button type="button" class="quantity-m">&minus;</button>
                                    <input type="number" id="quantity_{{ $product['id'] }}" class="quantity-add" value="1" aria-label="Cantidad del producto">
                                    <button type="button" class="quantity-p">&plus;</button>
                                </div>
                            </div>

                            <a href="javascript:void(0);" name="add-to-cart"
                               data-product-id="{{ $product['id'] ?? '' }}"
                               aria-label="Añadir al carrito: &ldquo;{{ $product['title'] ?? 'Producto' }}&rdquo;"
                               class="lv-btn lv-btn--primary lv-product__cta single_add_to_cart_button ajax_add_to_cart {{ !$product['in_stock'] ? 'disabled' : '' }}"
                               {{ !$product['in_stock'] ? 'disabled' : '' }}>
                                {{ $product['in_stock'] ? 'Añadir al carrito' : 'Agotado' }}
                            </a>

                            <button type="button"
                               class="lv-product__wishlist-btn wishlist-button {{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'wishlist-added' : '' }}"
                               data-product-id="{{ $product['id'] }}"
                               data-product-title="{{ $product['title'] }}"
                               data-product-price="{{ $product['price'] }}"
                               data-product-image="{{ asset($product['images'][0]) }}"
                               data-product-slug="{{ $product['slug'] }}"
                               aria-label="Añadir a la lista de deseos">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="{{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"></path>
                                </svg>
                                <span class="yith-wcwl-add-to-wishlist-button__label screen-reader-text">{{ in_array($product['id'], array_keys(Session::get('wishlist', []))) ? 'En la lista' : 'Añadir a la lista de deseos' }}</span>
                            </button>
                        </div>
                    </form>

                    @if($product['in_stock'])
                        <a href="javascript:void(0);" class="lv-btn lv-btn--ghost lv-product__buy-now" id="lv-buy-now">Comprar ahora</a>
                    @endif

                    <div class="d-flex flex-wrap gap-3 my-4">
                        <div class="d-flex align-items-center gap-3" style="flex:1 1 220px;">
                            <span class="d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:56px;height:56px;border-radius:50%;border:2px solid #3cb54a;">
                                <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true">
                                    <path d="M8.5 12.8L6 21l6-3 6 3-2.5-8.2" fill="#1c1c1c"/>
                                    <circle cx="12" cy="7.5" r="6" fill="#1c1c1c"/>
                                    <path d="M9.3 7.6l1.7 1.7 3.4-3.6" stroke="#fff" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span style="min-width:0;"><strong class="d-block" style="color:#3cb54a;font-style:italic;font-size:17px;">Calidad</strong><span style="color:#1c1c1c;font-size:14px;line-height:1.3;">Productos de calidad seleccionados</span></span>
                        </div>
                        <div class="d-flex align-items-center gap-3" style="flex:1 1 220px;">
                            <span class="d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:56px;height:56px;border-radius:50%;border:2px solid #3cb54a;">
                                <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10" fill="#1c1c1c"/>
                                    <text x="12" y="16.5" font-size="13" font-family="Arial, sans-serif" font-weight="700" fill="#fff" text-anchor="middle">&#8364;</text>
                                </svg>
                            </span>
                            <span style="min-width:0;"><strong class="d-block" style="color:#3cb54a;font-style:italic;font-size:17px;">Precio final</strong><span style="color:#1c1c1c;font-size:14px;line-height:1.3;">IVA incluido en todos los precios</span></span>
                        </div>
                        <div class="d-flex align-items-center gap-3" style="flex:1 1 220px;">
                            <span class="d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:56px;height:56px;border-radius:50%;border:2px solid #3cb54a;">
                                <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true">
                                    <path d="M1 6.5h12v9H1z" fill="#1c1c1c"/>
                                    <path d="M13 10.5h3.6l3.4 3v2h-7z" fill="#1c1c1c"/>
                                    <circle cx="6" cy="18" r="1.8" fill="#fff" stroke="#1c1c1c" stroke-width="1.6"/>
                                    <circle cx="17.5" cy="18" r="1.8" fill="#fff" stroke="#1c1c1c" stroke-width="1.6"/>
                                </svg>
                            </span>
                            <span style="min-width:0;"><strong class="d-block" style="color:#3cb54a;font-style:italic;font-size:17px;">Envío</strong><span style="color:#1c1c1c;font-size:14px;line-height:1.3;">{{ \App\Support\ShippingPolicy::shortLabel() }}</span></span>
                        </div>
                    </div>

                    <div class="lv-product__meta">
                        <span><strong>SKU:</strong> {{ $offer->sku }}</span>
                        @if ($offer->gtin)
                            <span><strong>EAN/GTIN:</strong> {{ $offer->gtin }}</span>
                        @endif
                        @if ($offer->brand)
                            <span><strong>Marca:</strong> {{ $offer->brand }}</span>
                        @endif
                        <span><strong>Categoría:</strong> <a href="{{ route('category', ['category' => $product['category']]) }}">{{ \App\Support\CategoryLabels::label($product['category']) }}</a></span>
                        @if(!empty($product['color']))
                            <span><strong>Color:</strong> {{ $product['color'] }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Specs table --}}
            <div class="lv-product__section">
                <h2 class="lv-product__section-title">Especificaciones técnicas</h2>
                <div class="lv-product__section-rule"></div>
                <table class="lv-specs">
                    <tr>
                        <th>SKU</th>
                        <td>{{ $offer->sku }}</td>
                    </tr>
                    @if ($offer->brand)
                        <tr>
                            <th>Marca</th>
                            <td>{{ $offer->brand }}</td>
                        </tr>
                    @endif
                    @if ($offer->gtin)
                        <tr>
                            <th>EAN/GTIN</th>
                            <td>{{ $offer->gtin }}</td>
                        </tr>
                    @endif
                    @if ($offer->mpn)
                        <tr>
                            <th>Referencia del fabricante</th>
                            <td>{{ $offer->mpn }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Categoría</th>
                        <td>{{ \App\Support\CategoryLabels::label($product['category']) }}</td>
                    </tr>
                    @if(!empty($product['color']))
                        <tr>
                            <th>Color</th>
                            <td>{{ $product['color'] }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Disponibilidad</th>
                        <td>{{ $offer->inStock ? 'En stock' : 'Agotado' }}</td>
                    </tr>
                    @if ($offer->unitPricingMeasure)
                        <tr>
                            <th>Cantidad</th>
                            <td>{{ str_replace('cbm', 'm³', $offer->unitPricingMeasure) }}</td>
                        </tr>
                    @endif
                </table>
            </div>

            {{-- Full description --}}
            @if(!empty($product['description']))
                <div class="lv-product__description">
                    {!! nl2br(e($product['description'])) !!}
                </div>
            @endif

            {{-- Related products --}}
            @if($relatedProducts->count() > 0)
                <div class="lv-related">
                    <h2 class="lv-related__title">Productos relacionados</h2>
                    <div class="products-grid">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="item">
                                <figure>
                                    <a href="{{ route('product.show', $relatedProduct['slug']) }}">
                                        <img src="{{ asset($relatedProduct['images'][0]) }}" alt="{{ $relatedProduct['title'] ?? '' }}" loading="lazy">
                                    </a>
                                </figure>
                                <div class="caption">
                                    <span class="price">
                                        {{ \App\Support\Money::format($relatedProduct['price']) }}&nbsp;&euro;
                                    </span>
                                    <h3 class="name">
                                        <a href="{{ route('product.show', $relatedProduct['slug']) }}">{{ $relatedProduct['title'] }}</a>
                                    </h3>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    @include('layouts.partials.footer.public')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Quantity selector
            const selector = document.querySelector('.quantity-selector');
            if (selector) {
                const input = selector.querySelector('.quantity-add');
                const minusBtn = selector.querySelector('.quantity-m');
                const plusBtn = selector.querySelector('.quantity-p');

                plusBtn.addEventListener('click', function () {
                    let value = parseInt(input.value, 10) || 1;
                    input.value = value + 1;
                });

                minusBtn.addEventListener('click', function () {
                    let value = parseInt(input.value, 10) || 1;
                    input.value = value > 1 ? value - 1 : 1;
                });

                input.addEventListener('input', function () {
                    let value = parseInt(input.value, 10);
                    if (isNaN(value) || value < 1) {
                        input.value = 1;
                    }
                });
            }

            // Gallery: thumbnail click + prev/next arrows
            const mainImg = document.getElementById('lv-gallery-main-img');
            const thumbs = document.querySelectorAll('.lv-gallery__thumb');
            let currentIndex = 0;

            function setActiveImage(index) {
                if (!thumbs.length) return;
                index = (index + thumbs.length) % thumbs.length;
                currentIndex = index;
                const thumb = thumbs[index];
                mainImg.src = thumb.dataset.src;
                thumbs.forEach(t => t.classList.remove('is-active'));
                thumb.classList.add('is-active');
            }

            thumbs.forEach((thumb, index) => {
                thumb.addEventListener('click', () => setActiveImage(index));
            });

            const prevArrow = document.querySelector('.lv-gallery__arrow--prev');
            const nextArrow = document.querySelector('.lv-gallery__arrow--next');
            if (prevArrow) prevArrow.addEventListener('click', () => setActiveImage(currentIndex - 1));
            if (nextArrow) nextArrow.addEventListener('click', () => setActiveImage(currentIndex + 1));

            // Buy now: add to cart then redirect to checkout
            const buyNowBtn = document.getElementById('lv-buy-now');
            if (buyNowBtn) {
                buyNowBtn.addEventListener('click', function () {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const quantity = document.querySelector('.quantity-add')?.value || 1;
                    buyNowBtn.textContent = 'Procesando...';

                    fetch("{{ route('cart.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            product_id: '{{ $product['id'] }}',
                            quantity: quantity
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = "{{ route('checkout') }}";
                            } else {
                                buyNowBtn.textContent = 'Comprar ahora';
                                alert(data.message || 'No se ha podido añadir el producto al carrito.');
                            }
                        })
                        .catch(() => {
                            buyNowBtn.textContent = 'Comprar ahora';
                            alert('Error de conexión.');
                        });
                });
            }
        });
    </script>
@endpush
