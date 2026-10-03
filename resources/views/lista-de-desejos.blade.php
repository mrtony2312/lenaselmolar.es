@extends('layouts.app')

@section('title', __('Lista de deseos'))

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

    <div id="tbay-main-content" class="mm-page mm-slideout mt-5">
        <div class="title-not-breadcrumbs">
            <div class="container">
                <h1 class="page-title">Lista de deseos</h1>
            </div>
        </div>

        <section id="main-container" class="container">
            <div class="row">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">
                        <form id="yith-wcwl-form" action="{{ route('wishlist.index') }}" method="post"
                              class="woocommerce yith-wcwl-form wishlist-fragment">
                            @if(count($wishlistProducts) > 0)
                                <table class="shop_table cart wishlist_table wishlist_view traditional responsive">
                                    <thead>
                                    <tr>
                                        <th class="product-thumbnail"></th>
                                        <th class="product-name">
                                            <span class="nobr">Nombre del producto</span>
                                        </th>
                                        <th class="product-price">
                                            <span class="nobr">Precio unitario</span>
                                        </th>
                                        <th class="product-stock-status">
                                            <span class="nobr">Estado del stock</span>
                                        </th>
                                        <th class="product-add-to-cart"></th>
                                        <th class="product-remove"></th>
                                    </tr>
                                    </thead>

                                    <tbody class="wishlist-items-wrapper">
                                    @foreach($wishlistProducts as $product)
                                        <tr id="yith-wcwl-row-{{ $product['id'] }}" data-row-id="{{ $product['id'] }}">
                                            <td class="product-thumbnail">
                                                <a href="{{ route('product.show', $product['slug']) ?? '#' }}">
                                                    <img loading="lazy" decoding="async" width="480" height="480"
                                                         src="{{ asset($product['images'][0] ?? $product['image'] ?? '') }}"
                                                         class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                         alt="{{ $product['title'] }}">
                                                </a>
                                            </td>
                                            <td class="product-name">
                                                <a href="{{ route('product.show', $product['slug']) ?? '#' }}">
                                                    {{ $product['title'] }}
                                                </a>
                                            </td>
                                            <td class="product-price">
                                                @if(isset($product['sale_price']) && $product['sale_price'] < $product['price'])
                                                    <del aria-hidden="true">
                                                        <span class="woocommerce-Price-amount amount">
                                                            {{ $product['price'] }}&nbsp;<span class="woocommerce-Price-currencySymbol">€</span>
                                                        </span>
                                                    </del>
                                                    <ins aria-hidden="true">
                                                        <span class="woocommerce-Price-amount amount">
                                                            {{ $product['sale_price'] }}&nbsp;<span class="woocommerce-Price-currencySymbol">€</span>
                                                        </span>
                                                    </ins>
                                                @else
                                                    <span class="woocommerce-Price-amount amount">
                                                        {{ $product['price'] }}&nbsp;<span class="woocommerce-Price-currencySymbol">€</span>
                                                    </span>
                                                @endif
                                                <small class="woocommerce-price-suffix">IVA incluido</small>
                                            </td>
                                            <td class="product-stock-status">
                                                <span class="wishlist-in-stock">En stock</span>
                                            </td>
                                            <td class="product-add-to-cart">
                                                <div class="group-buttons">
                                                    <div class="add-cart" title="Añadir">
                                                        <a href="javascript:void(0);"
                                                           data-product-id="{{ $product['id'] ?? '' }}"
                                                           class="wp-block-button__link add_to_cart_button ajax_add_to_cart"
                                                           aria-label="Añadir al carrito: &ldquo;{{ $product['title'] ?? 'Producto' }}&rdquo;">
                                                            <span class="title-cart">Añadir</span>
                                                            <i class="tb-icon tb-icon-bag-2"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="product-remove">
                                                <a href="#"
                                                   class="remove_from_wishlist"
                                                   data-product-id="{{ $product['id'] }}"
                                                   title="Eliminar este producto">
                                                    Eliminar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <table class="shop_table cart wishlist_table wishlist_view traditional responsive">
                                    <thead>
                                    <tr>
                                        <th class="product-thumbnail"></th>
                                        <th class="product-name">
                                            <span class="nobr">Nombre del producto</span>
                                        </th>
                                        <th class="product-price">
                                            <span class="nobr">Precio unitario</span>
                                        </th>
                                        <th class="product-stock-status">
                                            <span class="nobr">Estado del stock</span>
                                        </th>
                                        <th class="product-add-to-cart"></th>
                                        <th class="product-remove"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="wishlist-items-wrapper">
                                    <tr class="no-products">
                                        <td colspan="6" class="wishlist-empty text-center py-5">
                                            <div class="empty-wishlist-message">
                                                <i class="fa fa-heart" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                                                <h3>No se ha añadido ningún producto a la lista de deseos</h3>
                                                <p class="mt-3">Tu lista de deseos está vacía. Vuelve a la tienda para añadir productos.</p>
                                                <a href="{{ route('loja') }}" class="btn btn-primary mt-4">
                                                    <i class="fa fa-shopping-bag me-2"></i>Seguir comprando
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endif

                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('layouts.partials.footer.public')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            document.querySelectorAll('.remove_from_wishlist').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.dataset.productId;
                    removeProductFromWishlist(productId, this);
                });
            });

            function removeProductFromWishlist(productId, buttonElement) {
                const originalHtml = buttonElement.innerHTML;
                buttonElement.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                buttonElement.style.pointerEvents = 'none';

                fetch('{{ route("wishlist.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        product_id: productId.toString()
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        buttonElement.innerHTML = originalHtml;
                        buttonElement.style.pointerEvents = 'auto';

                        if (data.success) {
                            const desktopRow = document.querySelector(`#yith-wcwl-row-${productId}`);
                            if (desktopRow) {
                                desktopRow.style.transition = 'opacity 0.3s ease';
                                desktopRow.style.opacity = '0';
                                setTimeout(() => {
                                    desktopRow.remove();
                                    if (document.querySelectorAll('.wishlist-items-wrapper tr[data-row-id]').length === 0) {
                                        showEmptyWishlistMessage();
                                    }
                                }, 300);
                            }

                            document.querySelectorAll('.wishlist-count').forEach(element => {
                                element.textContent = data.count;
                                element.style.display = data.count > 0 ? 'inline-block' : 'none';
                            });

                            alert(data.message || 'Producto eliminado de la lista de deseos');
                        } else {
                            alert(data.message || 'Error al eliminar el producto');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        buttonElement.innerHTML = originalHtml;
                        buttonElement.style.pointerEvents = 'auto';
                        alert('Error de conexión con el servidor');
                    });
            }

            function showEmptyWishlistMessage() {
                const tbody = document.querySelector('.wishlist-items-wrapper');
                if (tbody) {
                    tbody.innerHTML = `
                        <tr class="no-products">
                            <td colspan="6" class="wishlist-empty text-center py-5">
                                <div class="empty-wishlist-message">
                                    <i class="fa fa-heart" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                                    <h3>No se ha añadido ningún producto a la lista de deseos</h3>
                                    <p class="mt-3">Tu lista de deseos está vacía. Vuelve a la tienda para añadir productos.</p>
                                    <a href="/" class="btn btn-primary mt-4">
                                        <i class="fa fa-shopping-bag me-2"></i>Seguir comprando
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;
                }
            }
        });
    </script>
@endpush
