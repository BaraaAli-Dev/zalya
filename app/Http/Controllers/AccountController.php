<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();

        $recentOrders = Order::where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $totalOrders = Order::where('user_id', $userId)->count();
        $pendingOrders = Order::where('user_id', $userId)
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        return Inertia::render('Store/Dashboard', [
            'recentOrders' => $recentOrders,
            'stats' => [
                'total' => $totalOrders,
                'pending' => $pendingOrders,
            ],
        ]);
    }
}
