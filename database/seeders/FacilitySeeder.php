<?php

namespace Database\Seeders;

use App\Models\Facility;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $facilities = [
            [
                'name' => 'Taytayan Camp Hut',
                'description' => "Welcome to our unique and cozy A-Frame Cabins, designed to offer you a relaxing and budget-friendly island experience. 
                Located just minutes from the pristine white sand beaches, our cabins combine simplicity with comfort to make your stay unforgettable.",
                'price' => 1200.00,
                'guests' => 2,
                'images' => [
                    'facilities/tchut (1).jpg',
                    'facilities/tchut (2).jpg',
                    'facilities/tchut (3).jpg',
                    'facilities/tchut (4).jpg',
                    'facilities/tchut (5).jpg',
                ],
                'location' => 'San Vicente, Olango Island',
                'amenities' => "✔️ Fully air-conditioned interiors
✔️ Comfortable beds with fresh linens
✔️ Private or shared bathroom options
✔️ Complimentary WiFi in common areas
✔️ Outdoor seating area with scenic views
✔️ Access to shared barbecue and picnic areas
✔️ Nearby convenience stores and dining options

Perfect for nature lovers and adventurers, our cabins provide a charming retreat where you can unwind under the shade of towering coconut trees and bask in the beauty of the islands sunsets. 🌴✨",
                'rental_start' => Carbon::createFromTime(6, 0, 0),
                'rental_end' => Carbon::createFromTime(20, 0, 0),
            ],
            [
                'name' => 'Brown House',
                'description' => "Escape to OLANGO for your long weekend getaway and experience a cozy, budget-friendly stay at Brown House! 
                Nestled in the heart of Sitio Suba, Barangay Sabang, our charming island retreat offers the perfect blend of comfort and affordability. 
                Reconnect with nature, unwind, and make lasting memories in this tranquil paradise. 🌴✨",
                'price' => 1200.00,
                'guests' => 2,
                'images' => [
                    'facilities/brown house (1).jpg',
                    'facilities/brown house (2).jpg',
                    'facilities/brown house (3).jpg',
                    'facilities/brown house (4).jpg',
                    'facilities/brown house (5).jpg',
                    'facilities/brown house (6).jpg',
                ],
                'location' => 'San Vicente, Olango Island',
                'amenities' => "
                All rooms are fully air-conditioned for your comfort!
                🏖️ Conveniently located just a short walk from stunning white sand beaches.
                🚗 Easily accessible as were located right along the main road.

                Amenities:
                ✔️ Free WiFi
                ✔️ Complimentary coffee/tea station
                ✔️ Outdoor lounging area
                ✔️ Secure parking
                ✔️ Mini-bar (available in selected rooms)
                ✔️ Hot and cold shower
                ✔️ 24/7 power backup
                ✔️ Nearby bike rentals and island tour packages

                Your cozy island retreat awaits! 🌴✨
                ",
                'rental_start' => Carbon::createFromTime(6, 0, 0),
                'rental_end' => Carbon::createFromTime(20, 0, 0),
            ]
        ];

        foreach ($facilities as $facility) {
            Facility::create([
                'name' => $facility['name'],
                'description' => $facility['description'],
                'price' => $facility['price'],
                'guests' => $facility['guests'],
                'images' => json_encode($facility['images']),
                'location' => $facility['location'],
                'amenities' => $facility['amenities'],
                'rental_start' => $facility['rental_start'],
                'rental_end' => $facility['rental_end'],
            ]);
        }

    }
}
