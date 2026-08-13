<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\OrderConfirmationMail;
use Inertia\Inertia;

class CheckoutController extends Controller
{
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
        ]);

        try {
            $order = DB::transaction(function () use ($validated, $cart) {
                // 1) قفل كل الـ Variants المطلوبة والتأكد من توفر الكمية أول حاجة
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

                // 2) لو كل حاجة متاحة، دلوقتي بس ننقص الـ Stock فعلياً
                $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

                $order = Order::create([
                    ...$validated,
                    'user_id' => Auth::id(),
                    'total_price' => $total,
                    'status' => 'pending',
                    'payment_method' => 'cash_on_delivery',
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

        Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));

        return redirect()->route('checkout.success', $order->id);
    }

    public function success(Order $order)
    {
        $order->load('items.product');

        return Inertia::render('Store/OrderSuccess', [
            'order' => $order,
        ]);
    }
}
