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

        // $directories = ['events', 'facilities', 'products'];
        // $dummy_file = public_path('images/dummy_image.jpg');

        // foreach($directories as $d) {
        //     Storage::makeDirectory('public/' . $d);
        //     $destination_path = 'public/' . $d .'/dummy_image.jpg';
        //     Storage::put($destination_path, File::get($dummy_file));
        // }

        Storage::makeDirectory('public/events');
        Storage::makeDirectory('public/products');
        Storage::makeDirectory('public/facilities');

        // $this->call(ModifierItemSeeder::class);
        // $this->call(ModifierGroupSeeder::class);
        // $this->call(ProductSeeder::class);
        $this->call(CategorySeeder::class);
        // $this->call(EventSeeder::class);
        // $this->call(FacilitySeeder::class);
        // $this->call(ConversationSeeder::class);

        //admin account
        User::factory()->create([
            'email' => 'admin@gmail.com',
            'first_name' => 'Hanni',
            'last_name' => 'Pham',
            'role' => 'admin',
            'password' => Hash::make('asdasd')
        ]);
        //cashier account
        User::factory()->create([
            'email' => 'cashier@gmail.com',
            'first_name' => 'Kim',
            'last_name' => 'Chaewon',
            'role' => 'cashier',
            'password' => Hash::make('asdasd')
        ]);
        //driver account
        User::factory()->create([
            'email' => 'driver@gmail.com',
            'first_name' => 'Daniel',
            'last_name' => 'Caesar',
            'role' => 'driver',
            'password' => Hash::make('asdasd')
        ]);
        //customer account :)
        // $user = User::factory()->create([
        //     'email' => 'sumagangjoren@gmail.com',
        //     'first_name' => 'Joren',
        //     'last_name' => 'Sumagang',
        //     'role' => 'customer',
        //     'password' => Hash::make('asdasd')
        // ]);
        
        // Cart::create([
        //     'user_id' => $user->id
        // ]);
        
    }
}
