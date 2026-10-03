<?php

namespace App\Domain\Cart;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\ProductPresenter;
use App\Support\Money;
use App\Support\ShippingPolicy;

/**
 * Session cart that stores only {product id => quantity}.
 *
 * Title, price, image and stock are read from the Catalog on every access,
 * so the cart and the checkout always charge the price shown on the product
 * page and sent to Google — never a copy taken when the item was added.
 */
class Cart
{
    public const MAX_QUANTITY = 999;

    public function __construct(private Catalog $catalog) {}

    /**
     * @throws CartException
     */
    public function add(int|string|null $productId, int $quantity = 1): array
    {
        $product = $this->catalog->find($productId);

        if (! $product) {
            throw new CartException('Producto no encontrado.', 404);
        }

        if (! $product['in_stock']) {
            throw new CartException('Este producto está agotado y no se puede comprar en este momento.', 409);
        }

        $quantities = $this->quantities();
        $id = $product['id'];
        $quantities[$id] = min(self::MAX_QUANTITY, ($quantities[$id] ?? 0) + max(1, $quantity));
        $this->store($quantities);

        return $this->lines()[$id];
    }

    public function update(int|string|null $productId, int $quantity): bool
    {
        $quantities = $this->quantities();
        $id = (int) $productId;

        if (! isset($quantities[$id])) {
            return false;
        }

        if ($quantity < 1) {
            unset($quantities[$id]);
        } else {
            $quantities[$id] = min(self::MAX_QUANTITY, $quantity);
        }

        $this->store($quantities);

        return true;
    }

    public function remove(int|string|null $productId): bool
    {
        return $this->update($productId, 0);
    }

    public function clear(): void
    {
        session()->forget('cart');
    }

    public function isEmpty(): bool
    {
        return $this->lines() === [];
    }

    /**
     * Cart lines priced from the catalog. Products withdrawn from sale are
     * dropped; out-of-stock products stay visible with available=false so
     * checkout can refuse them explicitly.
     *
     * @return array<int, array<string, mixed>>
     */
    public function lines(): array
    {
        $lines = [];
        $quantities = $this->quantities();
        $dropped = false;

        foreach ($quantities as $id => $quantity) {
            $product = $this->catalog->find($id);

            if (! $product) {
                unset($quantities[$id]);
                $dropped = true;

                continue;
            }

            $price = round(Money::toFloat($product['price']), 2);

            $lines[$id] = [
                'id' => $id,
                'sku' => ProductPresenter::sku($product),
                'title' => ProductPresenter::title($product),
                'price' => $price,
                'old_price' => $product['old_price'] ?? '',
                'quantity' => $quantity,
                'line_total' => round($price * $quantity, 2),
                'image' => $product['images'][0] ?? ($product['hover_image'] ?: null),
                'slug' => $product['slug'],
                'short_description' => $product['short_description'],
                'available' => $product['in_stock'],
            ];
        }

        if ($dropped) {
            $this->store($quantities);
        }

        return $lines;
    }

    public function hasUnavailableItems(): bool
    {
        foreach ($this->lines() as $line) {
            if (! $line['available']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array{items: int, subtotal: float, shipping: float|null, shipping_label: string, total: float, total_includes_shipping: bool}
     */
    public function totals(): array
    {
        $items = 0;
        $subtotal = 0.0;

        foreach ($this->lines() as $line) {
            $items += $line['quantity'];
            $subtotal += $line['line_total'];
        }

        $subtotal = round($subtotal, 2);
        $shipping = $items > 0 ? ShippingPolicy::amount() : null;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'shipping_label' => ShippingPolicy::amountLabel(),
            'total' => round($subtotal + ($shipping ?? 0.0), 2),
            'total_includes_shipping' => $shipping !== null,
        ];
    }

    /**
     * @return array<int, int>
     */
    private function quantities(): array
    {
        $out = [];

        foreach ((array) session('cart', []) as $key => $entry) {
            // Accept the legacy session shape (full product copy) too.
            $id = (int) (is_array($entry) ? ($entry['id'] ?? $key) : $key);
            $quantity = (int) (is_array($entry) ? ($entry['quantity'] ?? 0) : $entry);

            if ($id > 0 && $quantity > 0) {
                $out[$id] = min(self::MAX_QUANTITY, $quantity);
            }
        }

        return $out;
    }

    /**
     * @param  array<int, int>  $quantities
     */
    private function store(array $quantities): void
    {
        $cart = [];

        foreach ($quantities as $id => $quantity) {
            $cart[$id] = ['id' => $id, 'quantity' => $quantity];
        }

        session()->put('cart', $cart);
    }
}
