<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
// Email verification removed - no longer needed with phone/Google registration
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OTPVerificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PhoneRegistrationController;
// Email registration removed - users now register via Google OAuth or phone OTP
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Email registration removed - users now register via Google OAuth or phone OTP

    // Phone Registration Routes
    Route::get('register-phone', [PhoneRegistrationController::class, 'create'])
        ->name('register-phone');
    Route::post('register-phone', [PhoneRegistrationController::class, 'store']);

    // OTP Verification Routes
    Route::get('verify-otp', [OTPVerificationController::class, 'create'])
        ->name('verify-otp');
    Route::post('verify-otp', [OTPVerificationController::class, 'store']);
    Route::post('resend-otp', [OTPVerificationController::class, 'resend'])
        ->name('resend-otp');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Google OAuth Routes
    Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    // Email verification routes removed - no longer needed with phone/Google registration

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
