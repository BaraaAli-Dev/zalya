<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __construct(protected StripePaymentService $stripePaymentService) {}

    public function show()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('home');
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return Inertia::render('Store/Checkout', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_street' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_country' => 'required|string|max:255',
            'payment_method' => 'required|in:cash_on_delivery,bank_transfer,stripe',
        ]);

        if ($validated['payment_method'] === 'stripe' && ! $this->stripePaymentService->isConfigured()) {
            return back()->withErrors([
                'payment_method' => 'Stripe is not configured. Add STRIPE_SECRET to your .env file first.',
            ]);
        }

        try {
            $order = DB::transaction(function () use ($validated, $cart) {
                // 1) Check if all items are still available in stock
                $variantIds = collect($cart)->pluck('variant_id');
                $variants = ProductVariant::whereIn('id', $variantIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($cart as $item) {
                    $variant = $variants->get($item['variant_id']);

                    if (! $variant || $variant->stock < $item['quantity']) {
                        throw new \Exception("Sorry, \"{$item['product_name']}\" ({$item['size']}) is no longer available in the requested quantity.");
                    }
                }


                $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

                $order = Order::create([
                    ...$validated,
                    'user_id' => Auth::id(),
                    'total_price' => $total,
                    'status' => 'pending',
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'unpaid',
                ]);

                foreach ($cart as $item) {
                    $variant = $variants->get($item['variant_id']);
                    $variant->decrement('stock', $item['quantity']);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'product_variant_id' => $item['variant_id'],
                        'size' => $item['size'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                return $order;
            });
        } catch (\Exception $e) {
            return back()->withErrors(['cart' => $e->getMessage()]);
        }

        Session::forget('cart');

        // Notify admins about the new order
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewOrderNotification($order));
        }

        Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));

        if ($order->payment_method === 'stripe') {
            $checkoutSession = $this->stripePaymentService->createCheckoutSession($order);

            if ($checkoutSession && isset($checkoutSession->url)) {
                return redirect()->away($checkoutSession->url);
            }

            return back()->withErrors([
                'payment_method' => 'Stripe could not create a checkout session. Please try again.',
            ]);
        }

        if ($order->payment_method === 'bank_transfer') {
            return redirect()->route('checkout.bankTransfer', $order->id);
        }

        return redirect()->route('checkout.success', $order->id);
    }

    public function bankTransfer(Order $order)
    {
        $order->load('items.product');

        return Inertia::render('Store/BankTransfer', [
            'order' => $order,
        ]);
    }

    public function success(Order $order)
    {
        $order->load('items.product');

        return Inertia::render('Store/OrderSuccess', [
            'order' => $order,
        ]);
    }

    public function stripeWebhook(Request $request)
    {
        try {
            $this->stripePaymentService->handleWebhook(
                $request->getContent(),
                $request->header('Stripe-Signature'),
            );
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json(['message' => 'Invalid Stripe webhook.'], 400);
        }

        return response()->json(['received' => true]);
    }
}
