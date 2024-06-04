<?php

namespace Database\Seeders;

use App\Models\ModifierGroup;
use App\Models\ModifierGroupItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModifierGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $dummy_modifier_groups = [
            'Choose your drinks',
            'Choice of toppings',
            'Add ons',
            'Would you like uhh...'
        ];

        foreach($dummy_modifier_groups as $item) {
            $modifier_group = ModifierGroup::create([
                'name' => $item,
                'required' => fake()->boolean()
            ]);

            $no_of_items = fake()->numberBetween(2, 5);

            for($i = 1; $i <= $no_of_items; $i++) {
                ModifierGroupItem::create([
                    'modifier_group_id' => $modifier_group->id,
                    'modifier_item_id' => fake()->randomNumber(1, 11)
                ]);
            }

        }

    }
}
