<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Fetch all market prices daily at 3 AM
Schedule::command('market:fetch-all')->dailyAt('03:00');

// Fetch specific categories at different times to avoid overwhelming servers
Schedule::command('market:fetch-all --category=fruits_vegetables')->dailyAt('03:00');
Schedule::command('market:fetch-all --category=livestock')->dailyAt('03:30');
Schedule::command('market:fetch-all --category=chemicals')->dailyAt('04:00');
Schedule::command('market:fetch-all --category=equipment')->dailyAt('04:30');
Schedule::command('market:fetch-all --category=classifieds')->dailyAt('05:00');
