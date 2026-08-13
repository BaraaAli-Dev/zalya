<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return Inertia::render('Store/Cart', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $request->quantity ?? 1;
        $variant = ProductVariant::with('product')->findOrFail($request->product_variant_id);

        $cart = Session::get('cart', []);
        $id = (string) $variant->id;

        // احسب الكمية المطلوبة (اللي في السلة بالفعل + الجديدة)
        $currentInCart = $cart[$id]['quantity'] ?? 0;
        $requestedTotal = $currentInCart + $quantity;

        if ($variant->stock < $requestedTotal) {
            return back()->withErrors([
                'stock' => "Only {$variant->stock} left in stock.",
            ]);
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'variant_id' => $variant->id,
                'product_id' => $variant->product_id,
                'product_name' => $variant->product->product_name,
                'price' => $variant->price,
                'size' => $variant->size,
                'image' => $variant->product->images[0] ?? null,
                'quantity' => $quantity,
            ];
        }

        Session::put('cart', $cart);

        return back()->with('success', 'Added to cart');
    }

    public function update(Request $request, $variantId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = Session::get('cart', []);

        if (! isset($cart[$variantId])) {
            return back();
        }

        $variant = ProductVariant::findOrFail($variantId);
        $newQuantity = $request->quantity;

        if ($variant->stock < $newQuantity) {
            return back()->withErrors([
                'stock' => "Only {$variant->stock} left in stock.",
            ]);
        }

        $cart[$variantId]['quantity'] = $newQuantity;
        Session::put('cart', $cart);

        return back();
    }

    public function remove($variantId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$variantId]);
        Session::put('cart', $cart);

        return back()->with('success', 'Removed from cart');
    }
}
