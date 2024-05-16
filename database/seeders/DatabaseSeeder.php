<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        User::factory()->create([
            'email' => 'sumagangjoren@gmail.com',
            'first_name' => 'Joren',
            'last_name' => 'Sumagang',
            'role' => 'admin',
            'password' => Hash::make('jorenjoren')
        ]);
    }
}
