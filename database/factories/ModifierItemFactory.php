<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModifierItem>
 */
class ModifierItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $dummy_modifier_items = [
            'cookies',
            'strawberry',
            'mango',
            'sauce',
            'chocolate syrup',
            'magic sarap',
            'asin',
            'cream',
            'butter',
            'avocado',
            'hot sauce',
            'milk'
        ];  

        return [
            //
            'name' => fake()->randomElement($dummy_modifier_items),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(0, 10)
        ];
    }
}
