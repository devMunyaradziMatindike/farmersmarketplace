<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initialize default settings
        Setting::set('app_name', 'Musika Wedu', 'string', 'Application name');
        Setting::set('app_url', 'http://localhost', 'string', 'Application URL');
        Setting::set('mail_from_address', 'hello@example.com', 'string', 'Mail from address');
        Setting::set('mail_from_name', 'Musika Wedu', 'string', 'Mail from name');
    }
}
