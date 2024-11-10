<?php

namespace Database\Seeders;

use App\Models\ModifierGroup;
use App\Models\ModifierGroupItem;
use App\Models\ModifierItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModifierGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $menu = [
            [
                'group_name' => 'Choose your classic flavour milk tea size',
                'items' => [
                    ['name' => 'Petite', 'price' => 35.00],
                    ['name' => 'Moyen', 'price' => 55.00],
                    ['name' => 'Grand', 'price' => 65.00],
                ],
            ],
            [
                'group_name' => 'Choose your special flavour milk tea size',
                'items' => [
                    ['name' => 'Petite', 'price' => 45.00],
                    ['name' => 'Moyen', 'price' => 65.00],
                    ['name' => 'Grand', 'price' => 75.00],
                ],
            ],
            [
                'group_name' => 'Choose your frappe size',
                'items' => [
                    ['name' => 'Moyen', 'price' => 75.00],
                    ['name' => 'Grand', 'price' => 85.00],
                ],
            ],
            [
                'group_name' => 'Choose your halo-halo size',
                'items' => [
                    ['name' => 'Petite', 'price' => 35.00],
                    ['name' => 'Grand', 'price' => 45.00],
                ],
            ],
            [
                'group_name' => 'Choose your fruit shake size',
                'items' => [
                    ['name' => 'Moyen', 'price' => 55.00],
                    ['name' => 'Grand', 'price' => 65.00],
                ],
            ],
        ];
        
        foreach ($menu as $groupData) {
            // Create a ModifierGroup
            $modifier_group = ModifierGroup::create([
                'name' => $groupData['group_name'],
                'required' => true
            ]);
        
            // Loop through each item in the group
            foreach ($groupData['items'] as $itemData) {
                // Create a ModifierItem
                $modifier_item = ModifierItem::create([
                    'name' => $itemData['name'],
                    'price' => $itemData['price'],
                ]);
        
                // Associate ModifierItem with ModifierGroup
                ModifierGroupItem::create([
                    'modifier_group_id' => $modifier_group->id,
                    'modifier_item_id' => $modifier_item->id
                ]);
            }
        }

    }
}
