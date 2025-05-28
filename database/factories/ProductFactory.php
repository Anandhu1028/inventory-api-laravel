<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
        'name' => fake()->word(),
        'sku' => fake()->unique()->numerify('########'),
        'description' => fake()->sentence(),
        'base_price' => fake()->randomFloat(2, 100, 1000),
        'price' => fake()->randomFloat(2, 100, 2000), // <-- add this
        'stock' => fake()->numberBetween(1, 100),
    ];
    }
}
