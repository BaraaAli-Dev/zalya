<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_price'),
            'low_stock_count' => ProductVariant::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out_of_stock_count' => ProductVariant::where('stock', 0)->count(),
        ];

        $recentOrders = Order::orderBy('id', 'desc')->take(5)->get();

        $lowStockVariants = ProductVariant::with('product')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'lowStockVariants' => $lowStockVariants,
        ]);
    }
}
