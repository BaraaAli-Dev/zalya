<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Config;
use Stripe\Webhook;

class StripePaymentService
{
    public function __construct()
    {
        if (class_exists('\Stripe\Stripe')) {
            \Stripe\Stripe::setApiKey(Config::get('services.stripe.secret'));
        }
    }

    public function isConfigured(): bool
    {
        return (bool) Config::get('services.stripe.secret')
            && class_exists('\Stripe\Checkout\Session');
    }

    public function createCheckoutSession(Order $order): ?\stdClass
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'egp',
                    'product_data' => [
                        'name' => $item->product->product_name ?? 'Product',
                    ],
                    'unit_amount' => (int) round((float) $item->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => route('checkout.success', $order->id) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.show'),
            'customer_email' => $order->customer_email,
            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);

        return (object) [
            'url' => $session->url,
        ];
    }

    public function handleWebhook(string $payload, ?string $signature): void
    {
        $webhookSecret = Config::get('services.stripe.webhook_secret');

        if (! $webhookSecret || ! $signature) {
            throw new \RuntimeException('Stripe webhook is not configured.');
        }

        $event = Webhook::constructEvent($payload, $signature, $webhookSecret);

        if ($event->type !== 'checkout.session.completed') {
            return;
        }

        $orderId = $event->data->object->metadata->order_id ?? null;

        if ($orderId) {
            Order::whereKey($orderId)->update([
                'payment_status' => 'paid',
            ]);
        }
    }
}
