@extends('layouts.app')

@section('title', __('Pedido confirmado'))

@push('styles')
@endpush

@section('content')
    @include('layouts.partials.navbar.public-show')

    <div id="tbay-main-content" class="mm-page mm-slideout">
        <div class="title-not-breadcrumbs">
            <div class="container">
                <h1 class="page-title">Finalización de compra</h1>
            </div>
        </div>
        <section id="main-container" class="container">
            <div class="row ">
                <div id="main-content" class="main-page col-12">
                    <div id="main" class="site-main">
                        <div class="woocommerce">
                            <div class="woocommerce-order">


                                <p style="margin: 0;line-height: 31px;border: 1px solid var(--tb-border-color);border-bottom: 0;padding: 20px 26px;font-size: 17px;font-weight: 700;color: var(--button-color); display: flex;justify-content:space-between;"
                                    class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                                    ✅ Gracias. Hemos recibido tu pedido.</p>

                                <ul style="border: 1px solid var(--tb-border-color);border-top: 0;padding: 18px 24px 26px;display: flex;justify-content: space-between;"
                                    class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

                                    <li class="woocommerce-order-overview__order order">
                                        Número de pedido: <strong>{{ $order['order_number'] }}</strong>
                                    </li>

                                    <li class="woocommerce-order-overview__date date">
                                        Fecha: <strong>{{ $order['date'] }}</strong>
                                    </li>


                                    <li class="woocommerce-order-overview__total total">
                                        Total: <strong><span
                                                class="woocommerce-Price-amount amount"><bdi>{{ \App\Support\Money::format($order['total_price']) }}&nbsp;<span
                                                        class="woocommerce-Price-currencySymbol">€</span></bdi></span></strong>
                                    </li>

                                    <li class="woocommerce-order-overview__payment-method method">
                                        Método de pago: <strong>{{ $order['payment_method'] }}</strong>
                                    </li>

                                </ul>


                            </div>
                            <p>Estimado cliente:</p>
                            <p>Gracias por tu pedido. Para confirmarlo, realiza una transferencia por el importe total
                                indicando tu número de pedido como concepto y envíanos el justificante por correo electrónico a
                                 antes de la
                                entrega. Tu pedido no se enviará hasta que se reciba el pago.</p>

                            @if (config('bank.iban'))
                                <section class="woocommerce-bacs-bank-details">
                                    <h2 class="wc-bacs-bank-details-heading">Nuestros datos bancarios</h2>
                                    <h3 class="wc-bacs-bank-details-account-name">{{ config('bank.holder') }}</h3>
                                    <ul class="wc-bacs-bank-details order_details bacs_details">
                                        @if (config('bank.bank_name'))
                                            <li class="bank_name">Banco: <strong>{{ config('bank.bank_name') }}</strong></li>
                                        @endif
                                        <li class="iban">IBAN: <strong>{{ config('bank.iban') }}</strong></li>
                                        @if (config('bank.bic'))
                                            <li class="bic">BIC/SWIFT: <strong>{{ config('bank.bic') }}</strong></li>
                                        @endif
                                    </ul>
                                </section>
                            @endif

                            <p>Todo el equipo de Leñas El Molar C.B. te agradece tu confianza.</p>
                            <section class="woocommerce-order-details">

                                <h2 class="woocommerce-order-details__title">Detalles del pedido</h2>

                                <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

                                    <style>
                                        #textr {
                                            text-align: right !important;
                                        }
                                    </style>
                                    <thead>
                                        <tr>
                                            <th class="woocommerce-table__product-name product-name">Producto</th>
                                            <th class="woocommerce-table__product-table product-total" id="textr">Total
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($order['items'] as $item)
                                            <tr class="woocommerce-table__line-item order_item">

                                                <td class="woocommerce-table__product-name product-name ">
                                                    {{ $item['title'] ?? ($item['name'] ?? 'Producto') }} <strong
                                                        class="product-quantity">×&nbsp;{{ $item['quantity'] }}</strong>
                                                </td>

                                                <td class="woocommerce-table__product-total product-total" id="textr">
                                                    <span class="woocommerce-Price-amount amount"><bdi>{{ \App\Support\Money::format($item['price'] * $item['quantity']) }}&nbsp;<span
                                                                class="woocommerce-Price-currencySymbol">€</span></bdi></span>
                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th scope="row">Subtotal:</th>
                                            <td id="textr"><span
                                                    class="woocommerce-Price-amount amount">{{ \App\Support\Money::format($order['subtotal'] ?? $order['total_price']) }}&nbsp;<span
                                                        class="woocommerce-Price-currencySymbol">€</span></span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Envío:</th>
                                            <td id="textr">{{ $order['shipping_label'] ?? 'A confirmar' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{ ($order['total_includes_shipping'] ?? false) ? 'Total:' : 'Total productos (envío a confirmar):' }}</th>
                                            <td id="textr"><span
                                                    class="woocommerce-Price-amount amount">{{ \App\Support\Money::format($order['total_price']) }}&nbsp;<span
                                                        class="woocommerce-Price-currencySymbol">€</span></span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Método de pago:</th>
                                            <td id="textr">{{ $order['payment_method'] }}</td>
                                        </tr>
                                        @if (!empty($order['order_comments']))
                                            <tr>
                                                <th>Nota:</th>
                                                <td id="textr">
                                                    {{ $order['order_comments'] }} </td>
                                            </tr>
                                        @endif
                                    </tfoot>
                                </table>

                            </section>

                            <section class="woocommerce-customer-details">


                                <section
                                    class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
                                    <div
                                        class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">


                                        <h2 class="woocommerce-column__title">Dirección de facturación</h2>

                                        <address>
                                            {{ $order['billing']['first_name'] }} {{ $order['billing']['last_name'] }}
                                            <br>{{ $order['billing']['address_1'] }}<br>
                                            @if (!empty($order['billing']['address_2']))
                                                {{ $order['billing']['address_2'] }}<br>
                                            @endif
                                            {{ $order['billing']['city'] }}<br>
                                            {{ $order['billing']['postcode'] }}<br>
                                            {{ $order['billing']['state'] ?? '' }}<br>
                                            @if(!empty($order['billing']['nif']))DNI/NIF: {{ $order['billing']['nif'] }}<br>@endif
                                            {{ $order['billing']['country'] }}
                                            @if (!empty($order['billing']['phone']))
                                                <p class="woocommerce-customer-details--phone">
                                                    {{ $order['billing']['phone'] }}</p>
                                            @endif

                                            <p class="woocommerce-customer-details--email">
                                                {{ $order['customer']['email'] }}</p>

                                        </address>


                                    </div><!-- /.col-1 -->

                                    <div
                                        class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
                                        <h2 class="woocommerce-column__title">Dirección de envío</h2>
                                        <address>
                                            {{ $order['customer']['first_name'] }} {{ $order['customer']['last_name'] }}
                                            <br>{{ $order['customer']['address_1'] }}<br>
                                            @if (!empty($order['customer']['address_2']))
                                                {{ $order['customer']['address_2'] }}<br>
                                            @endif
                                            {{ $order['customer']['city'] }}<br>
                                            {{ $order['customer']['postcode'] }}<br>
                                            {{ $order['customer']['state'] ?? '' }}<br>
                                            @if(!empty($order['customer']['nif']))DNI/NIF: {{ $order['customer']['nif'] }}<br>@endif
                                            {{ $order['customer']['country'] }}
                                            @if (!empty($order['customer']['phone']))
                                                <p class="woocommerce-customer-details--phone">
                                                    {{ $order['customer']['phone'] }}</p>
                                            @endif
                                        </address>
                                    </div><!-- /.col-2 -->

                                </section><!-- /.col2-set -->


                            </section>


                        </div>
                    </div>
                </div><!-- .site-main -->

            </div><!-- .content-area -->
    </div>
    </section>

    </div>
    @include('layouts.partials.footer.public')

@endsection

@push('scripts')
<script>
    if (typeof gtag === 'function') {
        gtag('event', 'conversion', {
            'send_to': 'AW-17798780713/RgASCMbum9obEKmuj6dC'
        });
    }
</script>
@endpush
