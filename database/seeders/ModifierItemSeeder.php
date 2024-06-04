<?php

namespace Database\Seeders;

use App\Models\ModifierItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModifierItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $dummy_modifier_items = [
            'cookies',
            'strawberry',
            'mango',
            'sauce',
            'chocolate syrup',
            'magic sarap',
            'cream',
            'butter',
            'avocado',
            'hot sauce',
            'milk'
        ];  

        foreach($dummy_modifier_items as $item) {
            ModifierItem::create([
                'name' => $item,
                'description' => fake()->sentence(),
                'price' => fake()->numberBetween(0, 10)
            ]);
        }
    }
}
