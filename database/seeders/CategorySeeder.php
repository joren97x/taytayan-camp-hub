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
            'Milk Tea',
            'Frappe',
            'Halo-Halo',
            'Fruit Shake',
            'Fresh Lemonade',
            'Go To Foods',
            'Appetizers'
        ];
        
        foreach($dummy_categories as $item) {
            Category::create([ 'name' => $item]);
        }

    }
}
