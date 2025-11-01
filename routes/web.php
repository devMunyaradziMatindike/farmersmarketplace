<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Web\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Web\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Web\Admin\UserController as AdminUserController;
use App\Http\Controllers\Web\Buyer\DashboardController as BuyerDashboardController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Web\Seller\ProductController as SellerProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/sitemap-static.xml', [SitemapController::class, 'static']);
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories']);
Route::get('/sitemap-products.xml', [SitemapController::class, 'products']);

// Robots.txt
Route::get('/robots.txt', function () {
    $robots = "User-agent: *\n";
    $robots .= "Allow: /\n";
    $robots .= "Disallow: /admin/\n";
    $robots .= "Disallow: /seller/\n";
    $robots .= "Disallow: /buyer/\n";
    $robots .= "Disallow: /dashboard\n";
    $robots .= "Disallow: /profile\n";
    $robots .= "Disallow: /settings\n";
    $robots .= "\n";
    $robots .= "Sitemap: https://musikawedu.co.zw/sitemap.xml\n";

    return response($robots, 200, ['Content-Type' => 'text/plain']);
});

// Home and Products Routes (Public)
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Market Pricing Page (Public)
Route::get('/market-pricing', function () {
    $currencySettings = \App\Models\CurrencySetting::current();

    return Inertia::render('MarketPricing', [
        'exchangeRate' => $currencySettings?->zwg_to_usd_rate ?? 1.0,
    ]);
})->name('market.pricing');

// Events Routes (Public)
Route::get('/events', [\App\Http\Controllers\Web\EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [\App\Http\Controllers\Web\EventController::class, 'show'])->name('events.show');
// Registration can be done by guests (cooperatives, companies) or authenticated users
Route::post('/events/{event}/register', [\App\Http\Controllers\Web\EventController::class, 'register'])->name('events.register');

// Saved Searches Routes (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/saved-searches', [\App\Http\Controllers\Web\SavedSearchController::class, 'index'])->name('saved-searches.index');
    Route::post('/saved-searches', [\App\Http\Controllers\Web\SavedSearchController::class, 'store'])->name('saved-searches.store');
    Route::get('/saved-searches/{savedSearch}/execute', [\App\Http\Controllers\Web\SavedSearchController::class, 'execute'])->name('saved-searches.execute');
    Route::put('/saved-searches/{savedSearch}', [\App\Http\Controllers\Web\SavedSearchController::class, 'update'])->name('saved-searches.update');
    Route::delete('/saved-searches/{savedSearch}', [\App\Http\Controllers\Web\SavedSearchController::class, 'destroy'])->name('saved-searches.destroy');
});

// Registration Options Page (Public)
Route::get('/register', function () {
    return Inertia::render('Auth/Register');
})->name('register');

// Dashboard (Authenticated) - Role-based routing
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isSeller()) {
            return redirect()->route('seller.dashboard');
        } else {
            return redirect()->route('buyer.dashboard');
        }
    })->name('dashboard');
});

// Admin Routes (Authenticated + Admin Role)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', AdminUserController::class);
    Route::patch('users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::patch('users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');

    // Product Management
    Route::resource('products', AdminProductController::class)->only(['index', 'show', 'destroy']);
    Route::patch('products/{product}/approve', [AdminProductController::class, 'approve'])->name('products.approve');
    Route::patch('products/{product}/reject', [AdminProductController::class, 'reject'])->name('products.reject');

    // Category Management
    Route::resource('categories', AdminCategoryController::class);

    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/currency', [AdminSettingsController::class, 'updateCurrency'])->name('settings.currency');
});

// Seller Routes (Authenticated)
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

    // Product Management
    Route::resource('products', SellerProductController::class);
    // Product Photos
    Route::delete('products/{product}/photos/{photo}', [SellerProductController::class, 'deletePhoto'])
        ->name('products.photos.destroy');
    Route::patch('products/{product}/status', [SellerProductController::class, 'updateStatus'])->name('products.status');

    // Analytics
    Route::get('/analytics', [SellerDashboardController::class, 'analytics'])->name('analytics');

    // Orders
    Route::get('/orders', [SellerDashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [SellerDashboardController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status', [SellerDashboardController::class, 'updateOrderStatus'])->name('orders.status');

    // Messages
    Route::get('/messages', [SellerDashboardController::class, 'messages'])->name('messages');
    Route::get('/messages/{conversation}', [SellerDashboardController::class, 'showMessage'])->name('messages.show');
    Route::post('/messages/send', [SellerDashboardController::class, 'sendMessage'])->name('messages.send');
    Route::patch('/messages/{conversation}/read', [SellerDashboardController::class, 'markAsRead'])->name('messages.read');

    // Profile
    Route::get('/profile', [SellerDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [SellerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/photo', [SellerDashboardController::class, 'uploadPhoto'])->name('profile.photo');

    // Settings
    Route::get('/settings', [SellerDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [SellerDashboardController::class, 'updateSettings'])->name('settings.update');
});

// Buyer Routes (Authenticated)
Route::middleware(['auth'])->prefix('buyer')->name('buyer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [BuyerDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [BuyerDashboardController::class, 'updateProfile'])->name('profile.update');

    // Settings
    Route::get('/settings', [BuyerDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [BuyerDashboardController::class, 'updateSettings'])->name('settings.update');
});

// Profile Routes (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
