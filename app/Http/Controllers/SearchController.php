<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::with(['category', 'variants'])
            ->where('product_name', 'like', "%{$query}%")
            ->limit(8)
            ->get();

        return response()->json($products);
    }
}
