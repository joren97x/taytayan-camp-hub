<?php

namespace Database\Seeders;

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
        ModifierItem::factory(10)->create();
        Product::factory(10)->create();

        User::factory()->create([
            'email' => 'sumagangjoren@gmail.com',
            'first_name' => 'Joren',
            'last_name' => 'Sumagang',
            'role' => 'admin',
            'password' => Hash::make('jorenjoren')
        ]);
        User::factory()->create([
            'email' => 'user@gmail.com',
            'first_name' => 'Joren',
            'last_name' => 'Sumagang',
            'role' => 'customer',
            'password' => Hash::make('jorenjoren')
        ]);
    }
}
