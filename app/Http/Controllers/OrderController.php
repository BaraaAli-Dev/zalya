<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(10);

        return Inertia::render('Store/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items.product');

        return Inertia::render('Store/Orders/Show', [
            'order' => $order,
        ]);
    }
}
