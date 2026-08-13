<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StorefrontController extends Controller
{
    public function home(Request $request)
    {
        $query = Product::with(['category', 'variants'])->orderBy('id', 'desc');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $products = $query->paginate(12)->withQueryString();

        return Inertia::render('Store/Home', [
            'products' => $products,
            'categories' => Category::select('id', 'name')->get(),
            'filters' => [
                'category' => $request->category,
                'gender' => $request->gender,
            ],
        ]);
    }
}
