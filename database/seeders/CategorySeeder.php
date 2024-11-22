<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            
            'Milk Tea - Classic Flavours' => [
                ['name' => 'Hokkaido', 'description' => 'Smooth and creamy, a taste of Japan in every sip!', 'price' => 35],
                ['name' => 'Wintermelon', 'description' => 'Light and refreshing, perfect for cooling down any time of day.', 'price' => 35],
                ['name' => 'Okinawa', 'description' => 'A caramel twist to satisfy your sweet cravings.', 'price' => 35],
                ['name' => 'Matcha', 'description' => 'Bold and earthy, a treat for true matcha lovers!', 'price' => 35],
                ['name' => 'Strawberry Milktea', 'description' => 'Sweet, fruity, and oh-so-refreshing!', 'price' => 35],
                ['name' => 'Double Chocolate Milktea', 'description' => 'Double the chocolate, double the indulgence!', 'price' => 35],
            ],
            'Milk Tea - Special Flavours' => [
                ['name' => 'Oreo', 'description' => 'Crunchy, creamy, and cookie-delicious!', 'price' => 45],
                ['name' => 'Cheesecake', 'description' => 'A rich cheesecake experience in every sip.', 'price' => 45],
                ['name' => 'Oreo Cheesecake', 'description' => 'Your favorite cookie meets creamy cheesecake heaven!', 'price' => 45],
                ['name' => 'Red Velvet', 'description' => 'Decadently smooth with a hint of cocoa.', 'price' => 45],
                ['name' => 'Strawberry Cheesecake', 'description' => 'The perfect blend of fruity sweetness and creamy cheesecake.', 'price' => 45],
                ['name' => 'Matcha Cheesecake', 'description' => 'Matcha magic with a cheesecake twist!', 'price' => 45],
                ['name' => 'Chocolate Cheesecake', 'description' => 'Rich chocolate meets smooth cheesecake in a cup.', 'price' => 45],
            ],
            'Frappe' => [
                ['name' => 'Caramel', 'description' => 'Sweet, smooth, and irresistibly caramel.', 'price' => 75],
                ['name' => 'Strawberry', 'description' => 'Bright, fruity, and perfect for a summer treat.', 'price' => 75],
                ['name' => 'Oreo Cheesecake', 'description' => 'A creamy frappe with the crunch of Oreos and a cheesecake finish.', 'price' => 75],
                ['name' => 'Double Chocolate', 'description' => 'A double dose of chocolate bliss, icy and refreshing!', 'price' => 75],
            ],
            'Halo-Halo' => [
                ['name' => 'Special Halo-Halo', 'description' => 'A Filipino classic with extra goodness in every layer!', 'price' => 55],
                ['name' => 'Plain Halo-Halo', 'description' => 'Simple, sweet, and refreshing—the classic way!', 'price' => 35],
            ],
            'Fruit Shake' => [
                ['name' => 'Banana', 'description' => 'Creamy, dreamy banana goodness!', 'price' => 55],
                ['name' => 'Banana Apple', 'description' => 'A blend of banana and apple for a refreshing twist!', 'price' => 55],
                ['name' => 'Mango Graham', 'description' => 'Tropical mango delight with a graham twist!', 'price' => 55],
            ],
            'Fresh Lemonade' => [
                ['name' => 'Lemon', 'description' => 'Zesty, refreshing, and pure lemony goodness.', 'price' => 55],
                ['name' => 'Cucumber', 'description' => 'Cool, crisp, and perfectly refreshing!', 'price' => 55],
            ],
            'Go To Foods' => [
                ['name' => 'Sizzling Sisig with Rice and Egg', 'description' => 'Sizzle your taste buds with the ultimate Filipino favorite!', 'price' => 120],
                ['name' => 'Sizzling Sisig with Rice', 'description' => 'Get the perfect sizzle and crunch in every bite!', 'price' => 110],
                ['name' => 'Sizzling Humba ni Ma Akay with Rice', 'description' => 'A sweet and savory Filipino delight, served hot and hearty!', 'price' => 100],
                ['name' => 'Grilled Tuna Panga', 'description' => 'A smoky, flavorful grilled tuna treat thats big on taste!', 'price' => 180],
                ['name' => 'Ramen', 'description' => 'Warm, savory, and soul-soothing in every bowl.', 'price' => 85],
            ],
            'Appetizers' => [
                ['name' => 'French Fries', 'description' => 'Crispy, golden, and perfect for munching!', 'price' => 50],
                ['name' => 'Hamburger', 'description' => 'Juicy and flavorful, a classic bite youll love!', 'price' => 50],
                ['name' => 'Churros', 'description' => 'Sweet, crispy, and sprinkled with cinnamon goodness!', 'price' => 50],
                ['name' => 'Corndog', 'description' => 'A fun, savory snack on a stick!', 'price' => 50],
                ['name' => 'Ham & Egg Sandwich', 'description' => 'Simple yet satisfying, a breakfast classic anytime!', 'price' => 50],
                ['name' => 'Tempura', 'description' => 'Light, crispy, and packed with flavor in every crunch!', 'price' => 50],
            ],
        ];

        foreach ($categories as $categoryName => $products) {
            // Create the category
            $category = Category::create(['name' => $categoryName]);
            
            // Create the products and assign them to the category
            foreach ($products as $product) {
                $p = Product::create([
                    'name' => $product['name'],
                    'description' => $product['description'], // Add descriptions if needed
                    'photo' => "products/{$product['name']}.png", // Replace this with your image handling logic
                    'price' => $product['price'],
                    'is_featured' => rand(0,1) == 1,
                    'available' => true
                ]);

                CategoryProduct::create([
                    'product_id' => $p->id,
                    'category_id' => $category->id
                ]);

            }
        }

        // foreach ($products as $categoryName => $items) {
        //     $category = Category::create(['name' => $categoryName]);

        //     if ($category) {
        //         foreach ($items as $item) {
        //             Product::create([
        //                 'name' => $item['name'],
        //                 'description' => '', // Add descriptions if needed
        //                 'photo' => "products/{$item['name']}.png", // Replace this with your image handling logic
        //                 'price' => $item['price'],
        //                 'is_featured' => false,
        //                 'available' => true,
        //                 'category_id' => $category->id, // Ensure a foreign key for category is present in your products table
        //             ]);
        //         }
        //     }
        // }

    }
}
