<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        //admin account
        User::factory()->create([
            'email' => 'admin@gmail.com',
            'first_name' => 'Hanni',
            'last_name' => 'Pham',
            'role' => 'admin',
            'password' => Hash::make('jorenjoren')
        ]);
        //cashier account
        //customer account :)
        $user = User::factory()->create([
            'email' => 'sumagangjoren@gmail.com',
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
