<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Confirmación de tu pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 30px;
            background: #f9f9f9;
        }

        .order-info {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .address-box {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #2c3e50;
            margin: 10px 0;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .product-table th,
        .product-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .product-table th {
            background: #f5f5f5;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }

        .section-title {
            color: #2c3e50;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-top: 30px;
        }

        /* Styles pour le bouton WhatsApp */
        .whatsapp-button {
            display: inline-block;
            background-color: #25D366;
            color: white;
            text-decoration: none;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .whatsapp-button:hover {
            background-color: #1DA851;
            text-decoration: none;
            color: white;
        }

        .whatsapp-icon {
            vertical-align: middle;
            margin-right: 10px;
            font-size: 20px;
        }

        .whatsapp-container {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: white;
            border-radius: 10px;
            border: 2px solid #25D366;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Leñas El Molar C.B.</h1>
            <h2>Confirmación del pedido #{{ $order['order_number'] }}</h2>
        </div>

        <div class="content">
            <p>Hola {{ $order['customer']['first_name'] }},</p>
            <p>¡Gracias por tu pedido! Aquí tienes los detalles:</p>

            <!-- Bouton WhatsApp ajouté ici -->
            <div class="whatsapp-container">
                <h3>📱 ¿Necesitas ayuda?</h3>
                <p>¿Tienes alguna duda sobre tu pedido? ¡Contáctanos fácilmente por WhatsApp!</p>
                <a href="https://wa.me/34679245597?text=¡Hola! Tengo una consulta sobre mi pedido #{{ $order['order_number'] }}"
                    class="whatsapp-button" target="_blank">
                    <span class="whatsapp-icon">💬</span> Contactar por WhatsApp
                </a>
                <p style="margin-top: 10px; font-size: 14px; color: #666;">
                    <strong>Número:</strong> +34 679 24 55 97
                </p>
            </div>

            <div class="order-info">
                <h3>📋 Información del pedido</h3>
                <p><strong>Número de pedido:</strong> {{ $order['order_number'] }}</p>
                <p><strong>Fecha del pedido:</strong> {{ $order['date'] }}</p>
                <p><strong>Fecha completa:</strong> {{ $order['order_date'] }}</p>
                <p><strong>Método de pago:</strong> {{ $order['payment_method'] }}</p>
                <p><strong>Método de envío:</strong> {{ $order['shipping_method'] }}</p>
                @if (!empty($order['order_comments']))
                    <p><strong>Observaciones:</strong> {{ $order['order_comments'] }}</p>
                @endif
            </div>

            @if (config('bank.iban'))
                <div class="order-info">
                    <h3>🏦 Datos para la transferencia</h3>
                    <p>Indica tu número de pedido como concepto y envía el justificante a . El pedido se
                        prepara una vez recibido el pago.</p>
                    <p><strong>Titular:</strong> {{ config('bank.holder') }}</p>
                    @if (config('bank.bank_name'))
                        <p><strong>Banco:</strong> {{ config('bank.bank_name') }}</p>
                    @endif
                    <p><strong>IBAN:</strong> {{ config('bank.iban') }}</p>
                    @if (config('bank.bic'))
                        <p><strong>BIC/SWIFT:</strong> {{ config('bank.bic') }}</p>
                    @endif
                </div>
            @endif

            <h3 class="section-title">👤 Información del cliente</h3>
            <div class="order-info">
                <p><strong>Email:</strong> {{ $order['customer']['email'] }}</p>
                <p><strong>Teléfono:</strong> {{ $order['customer']['phone'] ?: 'No proporcionado' }}</p>
            </div>

            <h3 class="section-title">📍 Dirección de entrega</h3>
            <div class="address-box">
                <p><strong>{{ $order['customer']['first_name'] }} {{ $order['customer']['last_name'] }}</strong></p>
                <p>{{ $order['customer']['address_1'] }}</p>
                @if (!empty($order['customer']['address_2']))
                    <p>{{ $order['customer']['address_2'] }}</p>
                @endif
                <p>{{ $order['customer']['postcode'] }} {{ $order['customer']['city'] }}</p>
                <p>{{ $order['customer']['state'] ?? '' }}</p>
                @if(!empty($order['customer']['nif']))<p><strong>DNI/NIF:</strong> {{ $order['customer']['nif'] }}</p>@endif
                <p>{{ $order['customer']['country'] }}</p>
            </div>

            <h3 class="section-title">🏢 Dirección de facturación</h3>
            <div class="address-box">
                <p><strong>{{ $order['billing']['first_name'] }} {{ $order['billing']['last_name'] }}</strong></p>
                <p>{{ $order['billing']['address_1'] }}</p>
                @if (!empty($order['billing']['address_2']))
                    <p>{{ $order['billing']['address_2'] }}</p>
                @endif
                <p>{{ $order['billing']['postcode'] }} {{ $order['billing']['city'] }}</p>
                <p>{{ $order['billing']['state'] ?? '' }}</p>
                @if(!empty($order['billing']['nif']))<p><strong>DNI/NIF:</strong> {{ $order['billing']['nif'] }}</p>@endif
                <p>{{ $order['billing']['country'] }}</p>
                @if (!empty($order['billing']['phone']))
                    <p><strong>Teléfono:</strong> {{ $order['billing']['phone'] }}</p>
                @endif
            </div>

            <h3 class="section-title">🛒 Productos pedidos</h3>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order['items'] as $item)
                        <tr>
                            <td>{{ $item['title'] ?? ($item['name'] ?? 'Producto') }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ number_format($item['price'], 2, ',', '.') }} €</td>
                            <td>{{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                <p><strong>Cantidad total de artículos:</strong> {{ $order['total_items'] }}</p>
                <p><strong>Importe total:</strong> {{ $order['formatted_total_price'] }} €</p>
            </div>

            @if (!empty($order['order_comments']))
                <div class="order-info">
                    <h3>📝 Observaciones del pedido</h3>
                    <p>{{ $order['order_comments'] }}</p>
                </div>
            @endif

            <!-- Bouton WhatsApp répété avant la fermeture -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="https://wa.me/34679245597?text=¡Hola! Tengo una consulta sobre mi pedido #{{ $order['order_number'] }}"
                    class="whatsapp-button" target="_blank">
                    <span class="whatsapp-icon">💬</span> Contactar por WhatsApp
                </a>
            </div>

            <p>Nos pondremos en contacto contigo en breve para confirmar los detalles de la entrega.</p>
            <p>¡Gracias por elegir Leñas El Molar C.B.!</p>
        </div>

        <div class="footer">
            <p>Leñas El Molar C.B. &copy; {{ date('Y') }}</p>
            <p>Si tienes alguna duda, contáctanos: </p>
            <p><strong>WhatsApp:</strong> +34 679 24 55 97</p>
        </div>
    </div>
</body>

</html>
