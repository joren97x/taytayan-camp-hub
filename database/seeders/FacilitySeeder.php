<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $imageFiles = File::files(public_path('storage/facilities'));
        
        // Filter out only image files (optional, if your directory may contain non-image files)
        $imageFiles = array_filter($imageFiles, function ($file) {
            return in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif']);
        });

        $imageFilenames = array_map(function ($file) {
            return 'facilities/'.$file->getFilename();
        }, $imageFiles);

        for($i = 0; $i < 4; $i++) {

            $images = rand(1, 4);
            $image_paths = [];
            for($i = 0; $i < $images; $i++) {
                array_push($image_paths, fake()->randomElement($imageFilenames));
            }

            Facility::create([
                'name' => fake()->sentence(2),
                'description' => fake()->sentence(),
                'price' => fake()->numberBetween(10, 50),
                'guests' => fake()->numberBetween(1, 4),
                'images' => json_encode($image_paths)
            ]);
        }

    }
}
