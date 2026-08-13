<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'size' => $this->faker->randomElement(['30ml', '50ml', '100ml']),
            'price' => $this->faker->randomFloat(2, 200, 1500),
            'stock' => $this->faker->numberBetween(0, 50),
        ];
    }
}
