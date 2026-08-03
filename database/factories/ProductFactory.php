<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_name' => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'size' => $this->faker->randomElement(['S', 'M', 'L']),
            'description' => $this->faker->sentence(),
            'category_id' => Category::factory(),
            'stock' => $this->faker->numberBetween(0, 100),
            'slug' => $this->faker->slug(),
            'gender' => $this->faker->randomElement(['men', 'women', 'unisex']),
            'images' => $this->faker->imageUrl(),
        ];
    }
}
