<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Grains',
                'description' => 'Cereals and grain crops including maize, wheat, sorghum, millet, rice, barley, etc.',
            ],
            [
                'name' => 'Vegetables',
                'description' => 'Fresh vegetables including tomatoes, onions, cabbage, spinach, carrots, etc.',
            ],
            [
                'name' => 'Fruits',
                'description' => 'Fresh fruits including mangoes, bananas, oranges, apples, guavas, etc.',
            ],
            [
                'name' => 'Legumes',
                'description' => 'Pulses and legumes including beans, peas, groundnuts, soybeans, etc.',
            ],
            [
                'name' => 'Livestock',
                'description' => 'Live animals including cattle, goats, sheep, chickens, pigs, etc.',
            ],
            [
                'name' => 'Dairy Products',
                'description' => 'Milk and dairy products including fresh milk, cheese, yogurt, butter, etc.',
            ],
            [
                'name' => 'Poultry Products',
                'description' => 'Eggs and poultry products.',
            ],
            [
                'name' => 'Farm Equipment',
                'description' => 'Agricultural tools, machinery, and equipment.',
            ],
            [
                'name' => 'Seeds & Seedlings',
                'description' => 'Seeds, seedlings, and planting materials.',
            ],
            [
                'name' => 'Organic Products',
                'description' => 'Certified organic agricultural products.',
            ],
            [
                'name' => 'Agricultural Services',
                'description' => 'Farming consultation, land preparation, harvesting services, etc.',
            ],
            [
                'name' => 'Consultancy Services',
                'description' => 'Agricultural consultancy services from agronomists, lecturers, and farming experts. Get expert advice on crop management, soil analysis, farm planning, and more.',
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
