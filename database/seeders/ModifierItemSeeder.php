<?php

namespace Database\Seeders;

use App\Models\ModifierItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModifierItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ModifierItem::factory(10)->create();
    }
}
