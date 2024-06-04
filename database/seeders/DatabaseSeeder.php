<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\ModifierItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(CategorySeeder::class);
        $this->call(ModifierItemSeeder::class);
        $this->call(ModifierGroupSeeder::class);
        $this->call(ProductSeeder::class);

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
