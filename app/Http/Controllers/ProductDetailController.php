<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class ProductDetailController extends Controller
{
    public function show(Product $product)
    {
        $product->load('category', 'variants');

        return Inertia::render('Store/ProductDetails', [
            'product' => $product,
        ]);
    }
}
