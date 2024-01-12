<?php

namespace Database\Factories;

use App\Models\Bussiness;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'bussinesses_id' => Bussiness::all()->random()->id,
            'categories_id' => Category::all()->random()->id,
            'tittle' => $this->faker->unique()->sentence(),
            'description' => $this->faker->text(),
        ];
    }
}
