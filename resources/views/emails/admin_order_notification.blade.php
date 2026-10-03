<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nouvelle Commande Reçue</title>
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
            background: #e74c3c;
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
            border-left: 4px solid #3498db;
            margin: 10px 0;
        }

        .billing-box {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #e74c3c;
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

        .urgent {
            color: #e74c3c;
            font-weight: bold;
        }

        .section-title {
            color: #2c3e50;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ NOUVELLE COMMANDE REÇUE</h1>
            <h2>Commande #{{ $order['order_number'] }}</h2>
        </div>

        <div class="content">
            <p class="urgent">Une nouvelle commande a été reçue sur le site Leñas El Molar C.B. !</p>

            <div class="order-info">
                <h3>📋 Détails de la Commande</h3>
                <p><strong>Numéro de Commande :</strong> {{ $order['order_number'] }}</p>
                <p><strong>Date de la Commande :</strong> {{ $order['date'] }}</p>
                <p><strong>Date et Heure :</strong> {{ $order['order_date'] }}</p>
                <p><strong>Méthode de Paiement :</strong> {{ $order['payment_method'] }}</p>
                <p><strong>Méthode de Livraison :</strong> {{ $order['shipping_method'] }}</p>
                <p><strong>Quantité Totale d'Articles :</strong> {{ $order['total_items'] }}</p>
                <p><strong>Montant Total :</strong> {{ $order['formatted_total_price'] }} €</p>
                @if (!empty($order['order_comments']))
                    <p><strong>Remarques du Client :</strong> {{ $order['order_comments'] }}</p>
                @endif
            </div>

            <h3 class="section-title">👤 Informations du Client</h3>
            <div class="order-info">
                <p><strong>Email :</strong> {{ $order['customer']['email'] }}</p>
                <p><strong>Téléphone :</strong> {{ $order['customer']['phone'] ?: 'Non fourni' }}</p>
            </div>

            <h3 class="section-title">📍 Adresse de Livraison</h3>
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

            <h3 class="section-title">🏢 Adresse de Facturation</h3>
            <div class="billing-box">
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
                    <p><strong>Téléphone (Facturation) :</strong> {{ $order['billing']['phone'] }}</p>
                @endif
            </div>

            <h3 class="section-title">🛒 Produits Commandés</h3>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order['items'] as $item)
                        <tr>
                            <td>{{ $item['title'] ?? ($item['name'] ?? 'Produit') }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ number_format($item['price'], 2, ',', '.') }} €</td>
                            <td>{{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }} €</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Total :</strong></td>
                        <td><strong>{{ $order['formatted_total_price'] }} €</strong></td>
                    </tr>
                </tfoot>
            </table>

            <div class="order-info urgent">
                <h3>📞 Action Requise</h3>
                <p>Contactez le client pour confirmer la commande et planifier la livraison.</p>

                <p><strong>Informations de Contact :</strong></p>
                <ul>
                    <li><strong>Email :</strong> {{ $order['customer']['email'] }}</li>
                    <li><strong>Téléphone (Livraison) :</strong> {{ $order['customer']['phone'] ?: 'Non disponible' }}
                    </li>
                    @if (!empty($order['billing']['phone']) && $order['billing']['phone'] != $order['customer']['phone'])
                        <li><strong>Téléphone (Facturation) :</strong> {{ $order['billing']['phone'] }}</li>
                    @endif
                </ul>

                <p><strong>Méthode de Paiement :</strong> {{ $order['payment_method'] }}</p>
                <p><strong>Méthode de Livraison :</strong> {{ $order['shipping_method'] }}</p>
            </div>

            <div class="order-info">
                <h3>📝 Récapitulatif de la Commande</h3>
                <p><strong>ID de la Commande :</strong> {{ $order['order_number'] }}</p>
                <p><strong>Client :</strong> {{ $order['customer']['first_name'] }}
                    {{ $order['customer']['last_name'] }}</p>
                <p><strong>Montant Total :</strong> {{ $order['formatted_total_price'] }} €</p>
                <p><strong>Date :</strong> {{ $order['order_date'] }}</p>
            </div>
        </div>
    </div>
</body>

</html>
