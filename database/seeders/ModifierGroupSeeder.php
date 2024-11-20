<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ModifierGroup;
use App\Models\ModifierGroupItem;
use App\Models\ModifierGroupProduct;
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
                    ['name' => 'Petite', 'price' => 0.00],
                    ['name' => 'Moyen', 'price' => 20.00],
                    ['name' => 'Grand', 'price' => 30.00],
                ],
            ],
            [
                'group_name' => 'Choose your special flavour milk tea size',
                'items' => [
                    ['name' => 'Petite', 'price' => 0.00],
                    ['name' => 'Moyen', 'price' => 20.00],
                    ['name' => 'Grand', 'price' => 30.00],
                ],
            ],
            [
                'group_name' => 'Choose your frappe size',
                'items' => [
                    ['name' => 'Moyen', 'price' => 0.00],
                    ['name' => 'Grand', 'price' => 10.00],
                ],
            ],
            [
                'group_name' => 'Choose your halo-halo size',
                'items' => [
                    ['name' => 'Petite', 'price' => 0.00],
                    ['name' => 'Grand', 'price' => 10.00],
                ],
            ],
            [
                'group_name' => 'Choose your fruit shake size',
                'items' => [
                    ['name' => 'Moyen', 'price' => 0.00],
                    ['name' => 'Grand', 'price' => 10.00],
                ],
            ],
        ];
        
        foreach ($menu as $group_index => $groupData) {
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

            if ($group_index < 5) {
                // Get categories with products for IDs 1 to 5
                $categories = Category::with('products')->where('id', $group_index + 1)->get();
        
                foreach ($categories as $category) {
                    foreach ($category->products as $product) {
                        // Create ModifierGroupProduct with product and modifier group IDs
                        ModifierGroupProduct::create([
                            'product_id' => $product->id,
                            'modifier_group_id' => $modifier_group->id
                        ]);
                    }
                }
            }
        }

    }
}
