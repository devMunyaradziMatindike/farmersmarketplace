<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test seller
        $seller = User::where('role', 'seller')->first();

        if (! $seller) {
            $this->command->warn('No seller found. Please run DatabaseSeeder first.');

            return;
        }

        $categories = Category::all();

        // Demo products with real images from /farm folder
        $products = [
            // Grains
            [
                'category' => 'Grains',
                'products' => [
                    [
                        'name' => 'White Maize - 50kg Bags',
                        'description' => 'Premium quality white maize grown in Mashonaland. SC variety, moisture content below 12.5%. Perfect for sadza making. Harvested this season. Available in 50kg bags.',
                        'price' => 45.00,
                        'location' => 'Harare, Zimbabwe',
                        'latitude' => -17.8252,
                        'longitude' => 31.0335,
                        'contact_details' => '+263 77 123 4567',
                        'status' => 'available',
                        'photos' => ['maize-field.jpg'],
                    ],
                    [
                        'name' => 'Seedco Maize Seeds - SC513',
                        'description' => 'Certified Seedco maize seeds, SC513 variety. High yielding, drought resistant. Suitable for all regions. 10kg pack contains approximately 40,000 seeds.',
                        'price' => 120.00,
                        'location' => 'Bulawayo, Zimbabwe',
                        'latitude' => -20.1532,
                        'longitude' => 28.5789,
                        'contact_details' => '+263 77 234 5678',
                        'status' => 'available',
                        'photos' => ['seedco maize.png'],
                    ],
                ],
            ],

            // Vegetables
            [
                'category' => 'Vegetables',
                'products' => [
                    [
                        'name' => 'Fresh Farm Vegetables Mix',
                        'description' => 'Fresh organic vegetables straight from the farm. Mix includes tomatoes, onions, carrots, and cabbage. Harvested daily. No chemicals used.',
                        'price' => 15.00,
                        'location' => 'Gweru, Zimbabwe',
                        'latitude' => -19.4542,
                        'longitude' => 29.8158,
                        'contact_details' => '+263 77 345 6789',
                        'status' => 'available',
                        'photos' => ['freshfarmproduce.png', 'farm produce.png'],
                    ],
                    [
                        'name' => 'Organic Potatoes - 10kg',
                        'description' => 'Premium Irish potatoes grown in Nyanga. Large size, perfect for chips and cooking. Organic farming methods. Fresh from the farm.',
                        'price' => 25.00,
                        'location' => 'Nyanga, Zimbabwe',
                        'latitude' => -18.2148,
                        'longitude' => 32.7506,
                        'contact_details' => '+263 77 456 7890',
                        'status' => 'available',
                        'photos' => ['potatoes.png'],
                    ],
                ],
            ],

            // Livestock
            [
                'category' => 'Livestock',
                'products' => [
                    [
                        'name' => 'Brahman Bull - Breeding Stock',
                        'description' => 'Mature Brahman bull, 4 years old. Excellent breeding stock. Healthy, vaccinated, and ready for service. Good temperament. Registered pedigree.',
                        'price' => 1500.00,
                        'location' => 'Masvingo, Zimbabwe',
                        'latitude' => -20.0633,
                        'longitude' => 30.8317,
                        'contact_details' => '+263 77 567 8901',
                        'status' => 'available',
                        'photos' => ['brahman1.jpg', 'cattle.jpg', 'cattle3.jpg'],
                    ],
                    [
                        'name' => 'Boer Goats - Breeding Pair',
                        'description' => 'Quality Boer goat breeding pair. Male 2 years, female 18 months. Both vaccinated and dewormed. Excellent genetics for meat production.',
                        'price' => 450.00,
                        'location' => 'Mutare, Zimbabwe',
                        'latitude' => -18.9707,
                        'longitude' => 32.6707,
                        'contact_details' => '+263 77 678 9012',
                        'status' => 'available',
                        'photos' => ['boergoat.jpg', 'goats.jpg', '2 goats.png'],
                    ],
                    [
                        'name' => 'Dorper Sheep - 10 Head',
                        'description' => 'Dorper sheep for sale. 10 head available (8 ewes, 2 rams). All vaccinated. Good meat production. Hardy breed suitable for Zimbabwe climate.',
                        'price' => 2800.00,
                        'location' => 'Gwanda, Zimbabwe',
                        'latitude' => -20.9333,
                        'longitude' => 29.0167,
                        'contact_details' => '+263 77 789 0123',
                        'status' => 'available',
                        'photos' => ['sheep.jpg', 'sheepss.jpg'],
                    ],
                    [
                        'name' => 'Piglets - Large White Breed',
                        'description' => 'Healthy Large White piglets, 8 weeks old. Vaccinated and dewormed. Fast growing breed. Good for commercial farming. 15 piglets available.',
                        'price' => 80.00,
                        'location' => 'Kadoma, Zimbabwe',
                        'latitude' => -18.3333,
                        'longitude' => 29.9167,
                        'contact_details' => '+263 77 890 1234',
                        'status' => 'available',
                        'photos' => ['piglets.jpg', 'pigs.png', 'pigs2.png'],
                    ],
                ],
            ],

            // Poultry Products
            [
                'category' => 'Poultry Products',
                'products' => [
                    [
                        'name' => 'Free Range Chickens - 20 Birds',
                        'description' => 'Free range chickens, excellent layers. Average 280 eggs per year. Healthy, vaccinated. Mixed breeds. Great for backyard farming.',
                        'price' => 200.00,
                        'location' => 'Chitungwiza, Zimbabwe',
                        'latitude' => -18.0128,
                        'longitude' => 31.0725,
                        'contact_details' => '+263 77 901 2345',
                        'status' => 'available',
                        'photos' => ['free-range-chickens.jpg', 'chickens.png', 'chickens eating.png'],
                    ],
                    [
                        'name' => 'Road Runner Roosters',
                        'description' => 'Beautiful Road Runner roosters for sale. Excellent for breeding. Colorful plumage. Good genes. 3-6 months old.',
                        'price' => 35.00,
                        'location' => 'Norton, Zimbabwe',
                        'latitude' => -17.8833,
                        'longitude' => 30.7000,
                        'contact_details' => '+263 77 012 3456',
                        'status' => 'available',
                        'photos' => ['jongwe.png', 'huku.jpg'],
                    ],
                ],
            ],

            // Farm Equipment
            [
                'category' => 'Farm Equipment',
                'products' => [
                    [
                        'name' => 'Massey Ferguson 275 Tractor',
                        'description' => 'Massey Ferguson 275 tractor in excellent condition. 75HP, 4WD. Well maintained. New tires. Ready for ploughing, planting, and harvesting operations.',
                        'price' => 12000.00,
                        'location' => 'Chegutu, Zimbabwe',
                        'latitude' => -18.1333,
                        'longitude' => 30.1333,
                        'contact_details' => '+263 77 123 4568',
                        'status' => 'available',
                        'photos' => ['masseyFERGUSON.jpg', 'tractor.jpg', 'landini.jpg'],
                    ],
                    [
                        'name' => 'Combine Harvester - Hire Service',
                        'description' => 'Professional combine harvester for hire. Ideal for wheat, maize, soybean harvesting. Experienced operator included. Rate per hectare. Available for booking.',
                        'price' => 250.00,
                        'location' => 'Mazowe, Zimbabwe',
                        'latitude' => -17.5072,
                        'longitude' => 30.9728,
                        'contact_details' => '+263 77 234 5679',
                        'status' => 'available',
                        'photos' => ['combine-harvester.jpg', 'harvester.png'],
                    ],
                    [
                        'name' => 'Precision Planter - 4 Row',
                        'description' => '4-row precision planter for maize, beans, and soybeans. Adjustable row spacing. Fertilizer attachment included. Excellent condition.',
                        'price' => 3500.00,
                        'location' => 'Glendale, Zimbabwe',
                        'latitude' => -17.4167,
                        'longitude' => 31.0833,
                        'contact_details' => '+263 77 345 6780',
                        'status' => 'available',
                        'photos' => ['planter.jpg', 'planterrrrrr.png'],
                    ],
                    [
                        'name' => 'Pivot Irrigation System - 20 Hectares',
                        'description' => 'Center pivot irrigation system covering 20 hectares. Automatic control system. Solar powered. Excellent condition. Reduces water usage by 40%.',
                        'price' => 28000.00,
                        'location' => 'Alaska, Zimbabwe',
                        'latitude' => -17.9833,
                        'longitude' => 31.1167,
                        'contact_details' => '+263 77 456 7891',
                        'status' => 'available',
                        'photos' => ['pivot-irrigation.jpg', 'pivot.png', 'field under irrigation.png'],
                    ],
                ],
            ],

            // Seeds & Seedlings
            [
                'category' => 'Seeds & Seedlings',
                'products' => [
                    [
                        'name' => 'Certified Maize Seeds - 10kg',
                        'description' => 'Certified hybrid maize seeds. High germination rate (95%+). Drought resistant. Maturity: 120-130 days. Suitable for all regions.',
                        'price' => 85.00,
                        'location' => 'Borrowdale, Harare',
                        'latitude' => -17.8031,
                        'longitude' => 31.0672,
                        'contact_details' => '+263 77 567 8902',
                        'status' => 'available',
                        'photos' => ['seedco maize.png'],
                    ],
                ],
            ],

            // Agricultural Services
            [
                'category' => 'Agricultural Services',
                'products' => [
                    [
                        'name' => 'Land Preparation Service - Per Hectare',
                        'description' => 'Professional land preparation service. Includes ploughing, harrowing, and ridging. Experienced operators. Modern equipment. Rate per hectare.',
                        'price' => 45.00,
                        'location' => 'Chinhoyi, Zimbabwe',
                        'latitude' => -17.3667,
                        'longitude' => 30.2000,
                        'contact_details' => '+263 77 678 9013',
                        'status' => 'available',
                        'photos' => ['tractor.jpg'],
                    ],
                    [
                        'name' => 'Harvesting Service - Combine Harvester',
                        'description' => 'Professional harvesting service with modern combine harvester. Suitable for wheat, maize, sorghum. Experienced team. Competitive rates.',
                        'price' => 200.00,
                        'location' => 'Bindura, Zimbabwe',
                        'latitude' => -17.3019,
                        'longitude' => 31.3331,
                        'contact_details' => '+263 77 789 0124',
                        'status' => 'available',
                        'photos' => ['combine-harvester.jpg', 'harvester.png'],
                    ],
                ],
            ],

            // Dairy Products
            [
                'category' => 'Dairy Products',
                'products' => [
                    [
                        'name' => 'Fresh Farm Milk - Daily Delivery',
                        'description' => 'Fresh unpasteurized milk from grass-fed cows. Daily delivery available. 5 liters minimum order. Rich in nutrients. Delivered in sealed containers.',
                        'price' => 12.00,
                        'location' => 'Ruwa, Zimbabwe',
                        'latitude' => -17.8897,
                        'longitude' => 31.2450,
                        'contact_details' => '+263 77 890 1235',
                        'status' => 'available',
                        'photos' => ['cattle.jpg'],
                    ],
                ],
            ],

            // Organic Products
            [
                'category' => 'Organic Products',
                'products' => [
                    [
                        'name' => 'Organic Farm Produce Box',
                        'description' => 'Weekly organic vegetable box. Contains seasonal organic vegetables grown without chemicals. Tomatoes, onions, leafy greens, and more. Delivered fresh.',
                        'price' => 30.00,
                        'location' => 'Domboshava, Zimbabwe',
                        'latitude' => -17.6167,
                        'longitude' => 31.1333,
                        'contact_details' => '+263 77 901 2346',
                        'status' => 'available',
                        'photos' => ['freshfarmproduce.png', 'farm produce.png'],
                    ],
                ],
            ],

            // Fruits
            [
                'category' => 'Fruits',
                'products' => [
                    [
                        'name' => 'Fresh Seasonal Fruits',
                        'description' => 'Assorted fresh seasonal fruits. Currently available: mangoes, bananas, oranges. Pesticide-free. Sweet and juicy. Bulk orders available.',
                        'price' => 20.00,
                        'location' => 'Marondera, Zimbabwe',
                        'latitude' => -18.1851,
                        'longitude' => 31.5528,
                        'contact_details' => '+263 77 012 3457',
                        'status' => 'available',
                        'photos' => ['farm produce.png'],
                    ],
                ],
            ],

            // Legumes
            [
                'category' => 'Legumes',
                'products' => [
                    [
                        'name' => 'Sugar Beans - 25kg',
                        'description' => 'Premium quality sugar beans. Clean, sorted, and ready for cooking. Rich in protein. Perfect for commercial or household use. 25kg bags.',
                        'price' => 55.00,
                        'location' => 'Mvurwi, Zimbabwe',
                        'latitude' => -17.0333,
                        'longitude' => 30.8500,
                        'contact_details' => '+263 77 123 4569',
                        'status' => 'available',
                        'photos' => ['farm produce.png'],
                    ],
                ],
            ],
        ];

        foreach ($products as $categoryGroup) {
            $category = Category::where('name', $categoryGroup['category'])->first();

            if (! $category) {
                continue;
            }

            foreach ($categoryGroup['products'] as $productData) {
                $photos = $productData['photos'];
                unset($productData['photos']);

                $product = Product::create([
                    'user_id' => $seller->id,
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'location' => $productData['location'],
                    'latitude' => $productData['latitude'],
                    'longitude' => $productData['longitude'],
                    'contact_details' => $productData['contact_details'],
                    'status' => $productData['status'],
                    'date_listed' => now(),
                ]);

                // Add photos
                foreach ($photos as $index => $photoFile) {
                    // Use existing farm images - they're already in public/farm/
                    ProductPhoto::create([
                        'product_id' => $product->id,
                        'photo_path' => 'farm/'.$photoFile, // Reference existing images
                        'is_primary' => $index === 0,
                        'order' => $index,
                    ]);
                }
            }
        }

        $this->command->info('✅ Demo products seeded successfully with '.count($products).' categories!');
    }
}
