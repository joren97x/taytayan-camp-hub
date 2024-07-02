<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $imageFiles = File::files(public_path('storage/products'));

        // Filter out only image files (optional, if your directory may contain non-image files)
        $imageFiles = array_filter($imageFiles, function ($file) {
            return in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif']);
        });

        $imageFilenames = array_map(function ($file) {
            return 'products/'.$file->getFilename();
        }, $imageFiles);

        $dummy_products = [
            'Masarap Milktea',
            'Matamis Milktea',
            'Chocolate Ice Cream',
            'Melon Smoothie',
            'Matcha Milktea',
            'Wintermelon',
            'Strawberry Milktea',
            'Yogurt Drink',
            'Milk Cofee',
            'Matcha Tea Latte',
        ];

        foreach($dummy_products as $item) {
            Product::create([
                'name' => $item,
                'description' => fake()->sentence(),
                'price' => fake()->numberBetween(10, 50),
                'photo' => fake()->randomElement($imageFilenames)
            ]);
        }

    }
}
