<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function checkoutPayload(array $overrides = []): array
    {
        $address = [
            'country' => 'ES', 'first_name' => 'Ana', 'last_name' => 'López', 'address_1' => 'Calle Mayor 1',
            'city' => 'Madrid', 'state' => 'Madrid', 'postcode' => '28001',
        ];

        $payload = ['email' => 'ana@example.org', 'terms_checkbox' => '1', 'payment_method' => 'bacs'];

        foreach ($address as $key => $value) {
            $payload['shipping-'.$key] = $value;
            $payload['billing-'.$key] = $value;
        }

        return array_merge($payload, $overrides);
    }

    public function test_cart_always_charges_the_current_catalog_price(): void
    {
        $product = Product::factory()->create(['id' => 91001, 'slug' => 'p-91001', 'price' => '320.00']);

        $this->postJson(route('cart.add'), ['product_id' => 91001, 'quantity' => 2])
            ->assertOk()
            ->assertJsonPath('product.price', 320)
            ->assertJsonPath('totalPrice', '640.00');

        // Price changes after the item was added: the cart follows the catalog.
        $product->update(['price' => '330.00']);

        $this->getJson(route('cart.content'))
            ->assertJsonPath('cart.91001.price', 330)
            ->assertJsonPath('totalPrice', '660.00');
    }

    public function test_out_of_stock_product_cannot_be_added(): void
    {
        Product::factory()->create(['id' => 91002, 'slug' => 'p-91002', 'in_stock' => false]);

        $this->postJson(route('cart.add'), ['product_id' => 91002])
            ->assertStatus(409)
            ->assertJsonPath('success', false);
    }

    public function test_unverified_reference_price_is_never_struck_through_in_the_cart(): void
    {
        Product::factory()->create(['id' => 91003, 'slug' => 'p-91003', 'price' => '320.00', 'old_price' => '599.00']);

        $this->postJson(route('cart.add'), ['product_id' => 91003])->assertOk();

        $this->get(route('carrinho'))
            ->assertOk()
            ->assertDontSee('599')
            ->assertSee('320,00');
    }

    public function test_cart_and_checkout_show_unconfirmed_shipping_honestly(): void
    {
        Product::factory()->create(['id' => 91004, 'slug' => 'p-91004']);
        $this->postJson(route('cart.add'), ['product_id' => 91004])->assertOk();

        $this->get(route('carrinho'))->assertSee('A confirmar')->assertDontSee('Gratis');
        $this->get(route('checkout'))->assertSee('A confirmar')->assertDontSee('Gratis');
    }

    public function test_checkout_stores_the_order_with_a_sequential_number(): void
    {
        Mail::fake();
        Product::factory()->create(['id' => 91005, 'slug' => 'p-91005', 'price' => '320.00']);
        $this->postJson(route('cart.add'), ['product_id' => 91005, 'quantity' => 3])->assertOk();

        $this->post(route('checkout.store'), $this->checkoutPayload())
            ->assertRedirect(route('checkout.confirmation'));

        $order = Order::sole();
        $this->assertSame('LEM-'.str_pad((string) $order->id, 6, '0', STR_PAD_LEFT), $order->number);
        $this->assertSame('960.00', (string) $order->products_total);
        $this->assertSame(960.0, (float) $order->payload['total_price']);
        $this->assertFalse($order->payload['total_includes_shipping']);
        $this->assertSame([], session('cart', []));
    }

    public function test_checkout_requires_accepting_the_terms(): void
    {
        Product::factory()->create(['id' => 91006, 'slug' => 'p-91006']);
        $this->postJson(route('cart.add'), ['product_id' => 91006])->assertOk();

        $this->post(route('checkout.store'), $this->checkoutPayload(['terms_checkbox' => null]))
            ->assertSessionHasErrors('terms_checkbox');

        $this->assertSame(0, Order::count());
    }

    public function test_checkout_refuses_an_item_that_went_out_of_stock(): void
    {
        $product = Product::factory()->create(['id' => 91007, 'slug' => 'p-91007']);
        $this->postJson(route('cart.add'), ['product_id' => 91007])->assertOk();
        $product->update(['in_stock' => false]);

        $this->post(route('checkout.store'), $this->checkoutPayload())->assertRedirect(route('carrinho'));
        $this->assertSame(0, Order::count());
    }

    public function test_confirmed_shipping_is_added_to_the_order_total(): void
    {
        Mail::fake();
        config(['merchant.shipping.publish' => true, 'merchant.shipping.coverage_confirmed' => true, 'merchant.shipping.price' => '25.00']);
        Product::factory()->create(['id' => 91008, 'slug' => 'p-91008', 'price' => '320.00']);
        $this->postJson(route('cart.add'), ['product_id' => 91008])->assertOk()->assertJsonPath('totalPrice', '345.00');

        $this->post(route('checkout.store'), $this->checkoutPayload())->assertRedirect(route('checkout.confirmation'));
        $this->assertSame(345.0, (float) Order::sole()->payload['total_price']);
    }
}
