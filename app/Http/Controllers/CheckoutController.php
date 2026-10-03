<?php

namespace App\Http\Controllers;

use App\Domain\Cart\Cart;
use App\Mail\AdminOrderNotification;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Support\Money;
use App\Support\ShippingPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Delivery is only offered in Spain (merchant.shipping.country), so the
     * country selector must not suggest international delivery.
     */
    private const COUNTRIES = ['ES' => 'España'];

    public function show(Cart $cart)
    {
        $lines = $cart->lines();

        if ($lines === []) {
            return redirect()->route('carrinho')->with('error', 'Tu carrito está vacío.');
        }

        if ($cart->hasUnavailableItems()) {
            return redirect()->route('carrinho')->with('error', 'Tu carrito contiene productos agotados. Elimínalos para continuar.');
        }

        $totals = $cart->totals();

        return view('checkout', [
            'cart' => $lines,
            'totals' => $totals,
            'totalItems' => $totals['items'],
            'totalPrice' => $totals['total'],
            'formattedSubtotal' => Money::format($totals['subtotal']),
            'formattedTotalPrice' => Money::format($totals['total']),
            'isEmpty' => false,
            'pays' => self::COUNTRIES,
        ]);
    }

    public function confirmation()
    {
        $order = session()->get('last_order');

        if (! $order) {
            return redirect()->route('home');
        }

        return view('checkout.confirmation', [
            'order' => $order,
        ]);
    }

    public function store(Request $request, Cart $cart)
    {
        $countries = implode(',', array_keys(self::COUNTRIES));

        $validated = $request->validate([
            'order_notes' => 'nullable|string|max:1000',
            'email' => 'required|email|max:190',
            'terms_checkbox' => 'accepted',
            'shipping-country' => 'required|string|in:'.$countries,
            'shipping-first_name' => 'required|string|max:255',
            'shipping-last_name' => 'required|string|max:255',
            'shipping-address_1' => 'required|string|max:500',
            'shipping-address_2' => 'nullable|string|max:500',
            'shipping-city' => 'required|string|max:255',
            'shipping-state' => 'required|string|max:255',
            'shipping-nif' => 'nullable|string|max:50',
            'shipping-postcode' => 'required|string|max:20',
            'shipping-phone' => 'nullable|string|max:20',

            'billing-country' => 'required|string|in:'.$countries,
            'billing-first_name' => 'required|string|max:255',
            'billing-last_name' => 'required|string|max:255',
            'billing-address_1' => 'required|string|max:500',
            'billing-address_2' => 'nullable|string|max:500',
            'billing-city' => 'required|string|max:255',
            'billing-state' => 'nullable|string|max:255',
            'billing-nif' => 'nullable|string|max:50',
            'billing-postcode' => 'required|string|max:20',
            'billing-phone' => 'nullable|string|max:20',
        ], [
            'terms_checkbox.accepted' => 'Debes aceptar los términos y condiciones para realizar el pedido.',
        ]);

        // Prices are re-read from the catalog here: the order is charged the
        // price shown on the product page and sent to Google, never a copy
        // stored in the session earlier.
        $lines = $cart->lines();

        if ($lines === []) {
            return redirect()->route('carrinho')->with('error', 'Tu carrito está vacío.');
        }

        if ($cart->hasUnavailableItems()) {
            return redirect()->route('carrinho')->with('error', 'Tu carrito contiene productos agotados. Elimínalos para continuar.');
        }

        $totals = $cart->totals();

        $orderData = [
            'order_number' => null,
            'date' => now()->format('d/m/Y'),
            'shipping_method' => ShippingPolicy::confirmed()
                ? 'Envío estándar ('.ShippingPolicy::amountLabel().')'
                : 'Envío: coste y cobertura a confirmar antes de preparar el pedido',
            'shipping_label' => $totals['shipping_label'],
            'shipping_amount' => $totals['shipping'],
            'payment_method' => 'Transferencia bancaria',
            'customer' => [
                'email' => $validated['email'],
                'first_name' => $validated['shipping-first_name'],
                'last_name' => $validated['shipping-last_name'],
                'address_1' => $validated['shipping-address_1'],
                'address_2' => $validated['shipping-address_2'] ?? '',
                'city' => $validated['shipping-city'],
                'state' => $validated['shipping-state'],
                'nif' => strtoupper($validated['shipping-nif'] ?? ''),
                'postcode' => $validated['shipping-postcode'],
                'country' => $validated['shipping-country'],
                'phone' => $validated['shipping-phone'] ?? '',
            ],
            'billing' => [
                'first_name' => $validated['billing-first_name'],
                'last_name' => $validated['billing-last_name'],
                'address_1' => $validated['billing-address_1'],
                'address_2' => $validated['billing-address_2'] ?? '',
                'city' => $validated['billing-city'],
                'state' => $validated['billing-state'] ?? '',
                'nif' => strtoupper($validated['billing-nif'] ?? ''),
                'postcode' => $validated['billing-postcode'],
                'country' => $validated['billing-country'],
                'phone' => $validated['billing-phone'] ?? '',
            ],
            'items' => array_values($lines),
            'total_items' => $totals['items'],
            'subtotal' => $totals['subtotal'],
            'total_price' => $totals['total'],
            'total_includes_shipping' => $totals['total_includes_shipping'],
            'formatted_subtotal' => Money::format($totals['subtotal']),
            'formatted_total_price' => Money::format($totals['total']),
            'order_comments' => $validated['order_notes'] ?? '',
            'order_date' => now()->format('Y-m-d H:i:s'),
        ];

        // Persist first: an order must exist even if the e-mail fails.
        $order = DB::transaction(function () use ($orderData, $totals) {
            $order = Order::create([
                'number' => 'TMP-'.Str::uuid(),
                'email' => $orderData['customer']['email'],
                'status' => 'pending_payment',
                'products_total' => $totals['subtotal'],
                'payload' => $orderData,
            ]);

            // Sequential, unique, never reused: derived from the primary key.
            $orderData['order_number'] = 'LEM-'.str_pad((string) $order->id, 6, '0', STR_PAD_LEFT);
            $order->update(['number' => $orderData['order_number'], 'payload' => $orderData]);

            return $order;
        });

        $orderData = $order->payload;

        session()->put('last_order', $orderData);

        try {
            Mail::to($validated['email'])->send(new OrderConfirmation($orderData));

            $adminEmail = config('mail.admin_email', '');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminOrderNotification($orderData));
            }
        } catch (\Throwable $e) {
            // The order is already stored in `orders`; log the number only
            // (no personal data in logs).
            Log::error('Checkout mail failed for order '.$orderData['order_number'].': '.$e->getMessage());
        }

        $cart->clear();

        return redirect()->route('checkout.confirmation')->with([
            'success' => '¡Tu pedido se ha recibido correctamente!',
        ]);
    }
}
