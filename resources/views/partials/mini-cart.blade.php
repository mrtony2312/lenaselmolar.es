{{-- Mini cart (header dropdown / mobile drawer). Lines come from App\Domain\Cart\Cart: catalog prices, never a stale copy. --}}
<div class="mcart-border">
    @if (empty($lines))
        <ul class="cart_empty">
            <li><span>Tu carrito está vacío</span></li>
            <li class="total">
                <a class="button wc-continue" href="{{ route('loja') }}">
                    Seguir comprando
                    <i class="tb-icon tb-icon-angle-right"></i>
                </a>
            </li>
        </ul>
    @else
        <ul class="cart_list product_list_widget p-0">
            @foreach ($lines as $productId => $item)
                <li class="mini-cart-item mini_cart_item">
                    <div class="product-image">
                        <a class="image" href="{{ route('product.show', ['slug' => $item['slug']]) }}">
                            @if (!empty($item['image']))
                                <img width="100" height="100" src="{{ asset($item['image']) }}"
                                    class="attachment-woocommerce_gallery_thumbnail size-woocommerce_gallery_thumbnail"
                                    alt="{{ $item['title'] }}" decoding="async">
                            @endif
                        </a>
                    </div>
                    <div class="product-details">
                        <a class="product-name" href="{{ route('product.show', ['slug' => $item['slug']]) }}">
                            <span>{{ $item['title'] }}</span>
                        </a>
                        @unless ($item['available'])
                            <span class="lv-cart-unavailable">Agotado — retíralo para finalizar la compra</span>
                        @endunless
                        <div class="group">
                            <div class="quantity-wrap">
                                <div class="quantity">
                                    <label class="screen-reader-text" for="quantity_{{ $variant }}_{{ $productId }}">
                                        Cantidad de {{ $item['title'] }}
                                    </label>
                                    <span class="box">
                                        <div class="quantity-selector">
                                            <button type="button" class="quantity-minus" data-product-id="{{ $productId }}">−</button>
                                            <input type="number" class="quantity-input" id="quantity_{{ $variant }}_{{ $productId }}"
                                                data-product-id="{{ $productId }}" value="{{ $item['quantity'] }}" min="1"
                                                aria-label="Cantidad del producto">
                                            <button type="button" class="quantity-plus" data-product-id="{{ $productId }}">＋</button>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <span class="woocommerce-Price-amount amount">
                                <bdi>{{ \App\Support\Money::format($item['price']) }}&nbsp;<span class="woocommerce-Price-currencySymbol">€</span></bdi>
                            </span>
                        </div>
                        <a role="button" href="javascript:void(0);" class="remove mini-cart-remove"
                            data-product-id="{{ $productId }}" data-cart-type="{{ $variant }}"
                            aria-label="Eliminar {{ $item['title'] }} del carrito">
                            <i class="tb-icon tb-icon-trash"></i>
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="group-button">
            <p class="total">
                <strong>Subtotal:</strong>
                <span class="woocommerce-Price-amount amount">
                    <bdi>{{ \App\Support\Money::format($totals['subtotal']) }}&nbsp;<span class="woocommerce-Price-currencySymbol">€</span></bdi>
                </span>
            </p>
            <p class="total">
                <strong>Envío:</strong> <span>{{ $totals['shipping_label'] }}</span>
            </p>
            <p class="buttons">
                <a href="{{ route('carrinho') }}" class="button view-cart">Ver carrito</a>
                <a href="{{ route('checkout') }}" class="button checkout">Finalizar compra</a>
            </p>
        </div>
    @endif
    <div class="clearfix"></div>
</div>
