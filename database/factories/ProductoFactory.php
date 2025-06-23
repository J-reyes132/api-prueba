<?php

namespace Database\Factories;

use App\Models\Divisa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'divisa_id' => Divisa::factory(), // Assuming DivisaFactory exists
            'tax_cost' => $this->faker->randomFloat(2, 0, 100),
            'manufacturing_cost' => $this->faker->randomFloat(2, 0, 100),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
