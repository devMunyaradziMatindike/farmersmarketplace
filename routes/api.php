<?php

use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\V1\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\V1\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\V1\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\V1\Auth\GoogleAuthController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\OTPController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\Seller\ProductController as SellerProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

// Public routes - No authentication required
Route::prefix('v1')->group(function (): void {
    // Product routes (Public access for buyers)
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::get('products/search', [ProductController::class, 'search']);

    // Category routes (Public access)
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);

    // Search routes (Public access)
    Route::get('search/suggestions', [SearchController::class, 'suggestions']);
    Route::get('search/popular', [SearchController::class, 'popular']);
    Route::get('search', [SearchController::class, 'search']);

    // Authentication routes
    Route::prefix('auth')->group(function (): void {
        // Phone OTP Registration
        Route::post('register/phone', [RegisterController::class, 'registerWithPhone']);
        Route::post('verify-otp', [OTPController::class, 'verify']);
        Route::post('resend-otp', [OTPController::class, 'resend']);

        // Google OAuth
        Route::post('google', [GoogleAuthController::class, 'handleCallback']);

        // Login
        Route::post('login', [LoginController::class, 'login']);

        // Password Reset
        Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink']);
        Route::post('reset-password', [PasswordResetController::class, 'reset']);
    });
});

// Protected routes - Require authentication
Route::prefix('v1')->middleware('auth:sanctum')->group(function (): void {
    // Logout
    Route::post('auth/logout', [LoginController::class, 'logout']);

    // Seller routes
    Route::prefix('seller')->middleware('role:seller,admin')->group(function (): void {
        Route::get('products', [SellerProductController::class, 'index']);
        Route::post('products', [SellerProductController::class, 'store']);
        Route::get('products/{product}', [SellerProductController::class, 'show']);
        Route::put('products/{product}', [SellerProductController::class, 'update']);
        Route::delete('products/{product}', [SellerProductController::class, 'destroy']);
        Route::patch('products/{product}/status', [SellerProductController::class, 'updateStatus']);
        Route::post('products/{product}/photos', [SellerProductController::class, 'addPhotos']);
        Route::delete('products/{product}/photos/{photo}', [SellerProductController::class, 'deletePhoto']);
    });

    // Admin routes
    Route::prefix('admin')->middleware('role:admin')->group(function (): void {
        // User management
        Route::get('users', [AdminUserController::class, 'index']);
        Route::get('users/{user}', [AdminUserController::class, 'show']);

        // Category management
        Route::post('categories', [AdminCategoryController::class, 'store']);
        Route::put('categories/{category}', [AdminCategoryController::class, 'update']);
        Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy']);

        // Product management (Remove fraudulent listings)
        Route::delete('products/{product}', [AdminProductController::class, 'destroy']);

        // Search analytics
        Route::get('search/analytics', [SearchController::class, 'analytics']);
    });
});
