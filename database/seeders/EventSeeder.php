<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
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
       
        $events = [
            [
                'title' => 'Sabado Night With Out Homegrown Artists',
                'description' => "Get ready for another electrifying Sabado Night at Taytayan Camp! 🍻🍾💃🕺

Feel the island vibes as we bring you the ultimate weekend escape filled with music, dancing, and great company. 
Whether youre here to unwind, groove to the beats, or simply soak up the nightlife, this is your night to shine under the stars!

✨ Highlights:

🎶 Live music and DJ beats
🏝 Authentic island vibes
🍹 Drinks, laughter, and memories waiting to be made
💃 The perfect venue to dance the night away
🎟 Admission: Only ₱50.00

Tag your squad, bring your energy, and lets make this night unforgettable! 🌴
#SabadoNight #Octoberfest #IslandVibes #OlangoNightlife #TaytayanCamp #IslandLife #WhenInOlango",
                'location' => 'RJC, San Vicente, Olango Island',
                'date' => Carbon::today(),
                'start_time' => Carbon::createFromTime(20, 0, 0),
                'capacity' => 20,
                'admission_fee' => 50.00,
                'cover_photo' => 'events/sadado night.jpg'
            ],
            [
                'title' => 'Music Party With KABINGKA JADE LIVE!',
                'description' => 'Featuring:

Kabingka Jade Live
RJHung of Version Band Duo
Psytonix Band
Homegrown DJs

Details:
📅 Date: ' . Carbon::today() .'
⏰ Gates Open: 6:00 PM
📍 Venue: RJC Cafe

Admission Price:
🎟 Regular: ₱50.00

Tickets are available at RJC Cafe. Dont miss an unforgettable night of music and fun! 🎶',
                'location' => 'RJC, San Vicente, Olango Island',
                'date' => Carbon::today()->addWeek(),
                'start_time' => Carbon::createFromTime(20, 0, 0),
                'capacity' => 30,
                'admission_fee' => 150.00,
                'cover_photo' => 'events/music party.jpg',
                'max_ticket' => 5
            ],
            [
                'title' => 'DISCO NIGHT! BAYLE SA KALYE',
                'description' => 'Get ready to groove at the ultimate street party! 🎉✨ 
                Join us for Disco Night: Bayle sa Kalye on October 31, 8 PM, at RJC Café, San Vicente, Olango Island. 
                🌟Let the disco lights guide your moves as we dance the night away under the stars. 
                Powered by Tenfold Event Solutions, this is a night you wont want to miss! 🕺💃 See you there!',
                'location' => 'RJC, San Vicente, Olango Island',
                'date' => Carbon::today()->subWeek(),
                'start_time' => Carbon::createFromTime(20, 0, 0),
                'capacity' => 20,
                'admission_fee' => 50.00,
                'cover_photo' => 'events/disco party.jpg',
                'max_ticket' => 5,
                'status' => Event::STATUS_EVENT_ENDED
            ],
            [
                'title' => 'FRIDAY NIGHT PARTY!',
                'description' => 'Powered by: Tenfold

Details:
📅 Date: ' . Carbon::today()->addDay() . '
🌟 Theme: Long Weekend Fever

Join us at Taytayan Camp Island Adventure and RJC Cafe for an electrifying Friday night celebration. 
Dont miss out on the fun and excitement! 🎉',
                'location' => 'RJC, San Vicente, Olango Island',
                'date' => Carbon::today()->addDay(),
                'start_time' => Carbon::createFromTime(20, 0, 0),
                'capacity' => 50,
                'admission_fee' => 50.00,
                'cover_photo' => 'events/friday night.jpg'
            ],
            [
                'title' => 'LETS GO RETRO DISCO',
                'description' => 'Get ready to travel back in time for a groovy Lets Go Retro Disco Night at RJC, San Vicente, Olango Island! 🪩💃🕺

Feel the nostalgia as we bring you a night of disco fever, funky beats, and vibrant energy! Whether youre here to strut your stuff on the dance floor or soak up the retro vibes, this is your moment to shine!

✨ Highlights:

🎶 Live retro music and disco DJ mixes
🕺 Funky 70s outfits encouraged!
🍹 Refreshing drinks and delicious snacks
✨ A night full of dancing, laughter, and good vibes

🎟 Admission: ₱50.00

Gather your disco crew, dress to impress in your best retro attire, and lets boogie the night away! 🌈✨
#RetroDisco #LetsGoRetro #OlangoIslandEvents #RJCEvents #DanceTheNightAway',
                'location' => 'RJC, San Vicente, Olango Island',
                'date' => Carbon::today()->addDays(3),
                'start_time' => Carbon::createFromTime(20, 0, 0),
                'capacity' => 50,
                'admission_fee' => 50.00,
                'cover_photo' => 'events/friday night.jpg',
                'max_ticket' => 5,
            ],
            
        ];


        foreach ($events as $event) {
            Event::create([
                'title' => $event['title'],
                'description' => $event['description'],
                'location' => $event['location'],
                'date' => $event['date'],
                'start_time' => $event['start_time'],
                'capacity' => $event['capacity'],
                'admission_fee' => $event['admission_fee'],
                'cover_photo' => $event['cover_photo'],
            ]);
        }

    }

    
}
