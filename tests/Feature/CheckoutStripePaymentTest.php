<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\StripePaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Mockery;
use Tests\TestCase;

class CheckoutStripePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_choose_stripe_payment_method(): void
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

        $session = new \stdClass();
        $session->url = 'https://checkout.stripe.com/test_session';

        $stripeService = Mockery::mock(StripePaymentService::class);
        $stripeService->shouldReceive('isConfigured')->once()->andReturnTrue();
        $stripeService->shouldReceive('createCheckoutSession')->once()->andReturn($session);

        $this->app->instance(StripePaymentService::class, $stripeService);

        $response = $this->actingAs($user)->post('/checkout', [
            'customer_name' => 'Test User',
            'customer_email' => 'test@example.com',
            'customer_phone' => '01000000000',
            'shipping_street' => 'Street 1',
            'shipping_city' => 'Cairo',
            'shipping_state' => 'Cairo',
            'shipping_country' => 'Egypt',
            'payment_method' => 'stripe',
        ]);

        $response->assertRedirect('https://checkout.stripe.com/test_session');
        $this->assertDatabaseHas('orders', [
            'payment_method' => 'stripe',
            'customer_email' => 'test@example.com',
        ]);
    }
}
