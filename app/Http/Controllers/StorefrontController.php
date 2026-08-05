<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;

class StorefrontController extends Controller
{
    public function home()
    {
        return Inertia::render('Store/Home', [
            'featuredProducts' => Product::with('category')
                ->where('is_featured', true)
                ->latest()
                ->take(8)
                ->get(),

            'bestSellers' => Product::with('category')
                ->where('is_best_seller', true)
                ->latest()
                ->take(8)
                ->get(),

            'newArrivals' => Product::with('category')
                ->latest()
                ->take(8)
                ->get(),

            'categories' => Category::withCount('products')->get(),
        ]);
    }
}
