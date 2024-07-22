<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\ModifierItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // DO NOT CHANGE THE ORDER, WILL RUIN SOMETHING IF CHANGED

        $directories = ['events', 'facilities', 'products'];
        $dummy_file = public_path('images/dummy_image.jpg');

        foreach($directories as $d) {
            Storage::makeDirectory('public/' . $d);
            $destination_path = 'public/' . $d .'/dummy_image.jpg';
            
            Storage::put($destination_path, File::get($dummy_file));

        }

        Storage::makeDirectory('public/events');
        Storage::makeDirectory('public/products');
        Storage::makeDirectory('public/facilities');

        $destination_path = 'public/events/dummy_image.jpg';

        $this->call(ModifierItemSeeder::class);
        $this->call(ModifierGroupSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(EventSeeder::class);
        $this->call(FacilitySeeder::class);
        $this->call(ConversationSeeder::class);

        User::factory()->create([
            'email' => 'sumagangjoren@gmail.com',
            'first_name' => 'Joren',
            'last_name' => 'Sumagang',
            'role' => 'admin',
            'password' => Hash::make('jorenjoren')
        ]);

        $user = User::factory()->create([
            'email' => 'user@gmail.com',
            'first_name' => 'Joren',
            'last_name' => 'Sumagang',
            'role' => 'customer',
            'password' => Hash::make('jorenjoren')
        ]);

        Cart::create([
            'user_id' => $user->id
        ]);
        
    }
}
