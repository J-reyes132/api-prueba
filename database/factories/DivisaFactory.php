<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Divisa>
 */
class DivisaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $symbols = ['$', '€', '£', '¥', '₹', '₩', '₽', '₺', '₪', '₫'];
        $names = ['Dólar', 'Euro', 'Libra', 'Yen', 'Rupia', 'Won', 'Rublo', 'Lira Turca', 'Shekel', 'Dong Vietnamita'];
        return [
            'name' => $this->faker->randomElement($names),
            'code' => $this->faker->unique()->currencyCode(),
            'symbol' => $this->faker->randomElement($symbols),
            'exchange_rate' => $this->faker->randomFloat(8, 0, 100),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
