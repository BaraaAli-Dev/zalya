<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->safeEmail(),
            'customer_phone' => $this->faker->numerify('01#########'),
            'total_price' => $this->faker->randomFloat(2, 100, 3000),
            'status' => $this->faker->randomElement(['pending', 'processing', 'delivered', 'cancelled']),
            'shipping_street' => $this->faker->streetAddress(),
            'shipping_city' => $this->faker->city(),
            'shipping_state' => $this->faker->state(),
            'shipping_country' => 'Egypt',
            'payment_method' => 'cash_on_delivery',
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid']),
        ];
    }
}
