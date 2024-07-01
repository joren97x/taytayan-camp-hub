<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $imageFiles = File::files(public_path('images'));

        // Filter out only image files (optional, if your directory may contain non-image files)
        $imageFiles = array_filter($imageFiles, function ($file) {
            return in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif']);
        });

        $imageFilenames = array_map(function ($file) {
            return $file->getFilename();
        }, $imageFiles);

        $event = Event::create([
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(10),
            'location' => fake()->word(),
            'date' => fake()->date(),
            'start_time' => fake()->time(),
            'capacity' => 10,
            'admission_fee' => fake()->numberBetween(10, 50),
            'cover_photo' => fake()->randomElement($imageFilenames)
        ]);

        for($i = 0; $i < $event->capacity; $i++) {
            Ticket::create([
                'event_id' => $event->id,
                'ticket_code' => Str::random(15)
            ]);
        }

    }

    
}
