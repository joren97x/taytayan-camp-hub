<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $dummy_categories = [
            'Milktea',
            'Fruit Tea',
            'Smoothie',
            'Fruit shake'
        ];
        
        foreach($dummy_categories as $item) {
            Category::create([ 'name' => $item]);
            // CategoryProduct::create([
            //     'category_id' => $category->id,
            //     'product_id' => fake()->numberBetween(1, 10)
            // ]);
        }

    }
}
