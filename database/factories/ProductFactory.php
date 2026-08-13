<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = ucfirst($this->faker->word()) . ' ' . $this->faker->randomElement(['Eau de Parfum', 'Cologne', 'Perfume']);

        return [
            'product_name' => $name,
            'description' => $this->faker->sentence(),
            'category_id' => Category::factory(),
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'gender' => $this->faker->randomElement(['men', 'women', 'unisex']),
            'images' => [],
        ];
    }
}
