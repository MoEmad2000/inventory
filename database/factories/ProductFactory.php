<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'sku' => $this->faker->unique()->uuid,
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(100, 10000),
            'stock_quantity' => $this->faker->numberBetween(10, 100),            
            'low_stock_threshold' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
