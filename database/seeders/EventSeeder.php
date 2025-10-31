<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'title' => 'Zimbabwe Agricultural Expo 2025',
            'description' => 'Join us for the largest agricultural exhibition in Zimbabwe! Connect with farmers, suppliers, and industry experts. Discover the latest farming technologies, sustainable practices, and network with key players in Zimbabwe\'s agricultural sector.',
            'type' => 'exhibition',
            'image' => '/farm/freshfarmproduce.png',
            'start_date' => now()->addMonths(2)->format('Y-m-d H:i:s'),
            'end_date' => now()->addMonths(2)->addDays(3)->format('Y-m-d H:i:s'),
            'location' => 'Harare Exhibition Centre, Harare',
            'latitude' => -17.8292,
            'longitude' => 31.0522,
            'capacity' => 500,
            'registration_fee' => 25.00,
            'currency' => 'USD',
            'is_registration_open' => true,
            'is_featured' => true,
            'registration_instructions' => 'Please arrive 30 minutes early. Bring valid ID. Payment can be made via bank transfer or cash on arrival.',
            'organizer_name' => 'Zimbabwe Farmers Union',
            'organizer_contact' => '+263 4 123 4567 | info@zimbabwefarmers.zw',
        ]);

        Event::create([
            'title' => 'Modern Farming Techniques Workshop',
            'description' => 'Learn sustainable farming practices, irrigation systems, and crop rotation techniques from experienced agricultural experts. Perfect for both new and experienced farmers.',
            'type' => 'workshop',
            'image' => '/farm/irrigation1.png',
            'start_date' => now()->addWeeks(3)->format('Y-m-d 09:00:00'),
            'end_date' => now()->addWeeks(3)->format('Y-m-d 17:00:00'),
            'location' => 'Gweru Agricultural College, Gweru',
            'latitude' => -19.4556,
            'longitude' => 29.8122,
            'capacity' => 100,
            'registration_fee' => 0.00,
            'currency' => 'USD',
            'is_registration_open' => true,
            'is_featured' => false,
            'registration_instructions' => 'Free workshop open to all farmers. Bring your notebook and questions!',
            'organizer_name' => 'Ministry of Agriculture',
            'organizer_contact' => '+263 54 987 6543',
        ]);

        Event::create([
            'title' => 'Livestock Health & Management Training',
            'description' => 'Comprehensive training on livestock health, vaccination schedules, feeding programs, and disease prevention. Ideal for livestock farmers and cooperatives.',
            'type' => 'training',
            'image' => '/farm/cattle2.jpg',
            'start_date' => now()->addMonths(1)->format('Y-m-d 08:00:00'),
            'end_date' => now()->addMonths(1)->addDays(2)->format('Y-m-d 16:00:00'),
            'location' => 'Bulawayo Agricultural Showgrounds, Bulawayo',
            'latitude' => -20.1569,
            'longitude' => 28.5822,
            'capacity' => 150,
            'registration_fee' => 15.00,
            'currency' => 'USD',
            'is_registration_open' => true,
            'is_featured' => true,
            'registration_instructions' => 'Early registration recommended. Includes course materials and certificate of attendance.',
            'organizer_name' => 'Livestock Development Trust',
            'organizer_contact' => '+263 9 876 5432 | training@livestocktrust.zw',
        ]);
    }
}
