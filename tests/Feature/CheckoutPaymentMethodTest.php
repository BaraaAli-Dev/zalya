<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CheckoutPaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_choose_bank_transfer_payment_method(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'price' => 120,
        ]);

        Session::put('cart', [
            [
                'variant_id' => $variant->id,
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'size' => $variant->size,
                'quantity' => 1,
                'price' => 120,
                'image' => null,
            ],
        ]);

        $response = $this->actingAs($user)->post('/checkout', [
            'customer_name' => 'Test User',
            'customer_email' => 'test@example.com',
            'customer_phone' => '01000000000',
            'shipping_street' => 'Street 1',
            'shipping_city' => 'Cairo',
            'shipping_state' => 'Cairo',
            'shipping_country' => 'Egypt',
            'payment_method' => 'bank_transfer',
        ]);

        $response->assertRedirect(route('checkout.bankTransfer', ['order' => 1], false));
        $this->assertDatabaseHas('orders', [
            'payment_method' => 'bank_transfer',
            'customer_email' => 'test@example.com',
        ]);
    }
}
