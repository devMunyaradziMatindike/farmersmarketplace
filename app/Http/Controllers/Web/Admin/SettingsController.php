<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurrencySetting;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(Request $request): Response
    {
        // Get current settings from database or fallback to config
        $settings = [
            'app_name' => Setting::get('app_name', config('app.name')),
            'app_url' => Setting::get('app_url', config('app.url')),
            'mail_from_address' => Setting::get('mail_from_address', config('mail.from.address')),
            'mail_from_name' => Setting::get('mail_from_name', config('mail.from.name')),
            'twilio_enabled' => !empty(config('services.twilio.sid')),
            'google_oauth_enabled' => !empty(config('services.google.client_id')),
        ];

        // Get currency settings
        $currencySettings = CurrencySetting::current() ?? new CurrencySetting([
            'base_currency' => 'USD',
            'zwg_to_usd_rate' => 13.5000,
        ]);

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings,
            'currencySettings' => $currencySettings,
            'flash' => [
                'success' => session('success'),
            ],
        ]);
    }

    /**
     * Update settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_url' => ['required', 'url'],
            'mail_from_address' => ['required', 'email'],
            'mail_from_name' => ['required', 'string', 'max:255'],
        ]);

        // Save settings to database
        Setting::set('app_name', $request->app_name, 'string', 'Application name');
        Setting::set('app_url', $request->app_url, 'string', 'Application URL');
        Setting::set('mail_from_address', $request->mail_from_address, 'string', 'Mail from address');
        Setting::set('mail_from_name', $request->mail_from_name, 'string', 'Mail from name');

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Update currency settings.
     */
    public function updateCurrency(Request $request): RedirectResponse
    {
        $request->validate([
            'base_currency' => ['required', 'in:USD,ZWG'],
            'zwg_to_usd_rate' => ['required', 'numeric', 'min:0'],
        ]);

        $settings = CurrencySetting::first();

        if ($settings) {
            $settings->update([
                'base_currency' => $request->base_currency,
                'zwg_to_usd_rate' => $request->zwg_to_usd_rate,
                'rate_updated_at' => now(),
            ]);
        } else {
            CurrencySetting::create([
                'base_currency' => $request->base_currency,
                'zwg_to_usd_rate' => $request->zwg_to_usd_rate,
                'rate_updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Currency settings updated successfully.');
    }
}

