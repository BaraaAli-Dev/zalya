<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()->count(20)->create()->each(function ($product) {
            $sizes = collect(['30ml', '50ml', '100ml'])->random(rand(1, 3));

            foreach ($sizes as $size) {
                ProductVariant::factory()->create([
                    'product_id' => $product->id,
                    'size' => $size,
                ]);
            }
        });
    }
}
