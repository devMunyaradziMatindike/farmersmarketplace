<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SellerEmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_registration_requires_email_verification(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Seller',
            'email' => 'seller@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'seller',
        ]);

        // Should not be authenticated (not logged in)
        $this->assertGuest();
        
        // Should redirect to verification notice
        $response->assertRedirect(route('verification.notice'));
        
        // User should exist but not be verified
        $user = User::where('email', 'seller@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->hasVerifiedEmail());
        $this->assertTrue($user->isSeller());
    }

    public function test_buyer_registration_allows_immediate_access(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Buyer',
            'email' => 'buyer@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'buyer',
        ]);

        // Should be authenticated (logged in)
        $this->assertAuthenticated();
        
        // Should redirect to dashboard
        $response->assertRedirect(route('dashboard'));
        
        // User should exist and be verified
        $user = User::where('email', 'buyer@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isBuyer());
    }

    public function test_seller_can_verify_email(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'role' => 'seller',
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('login'));
    }

    public function test_verification_notice_shows_seller_specific_content(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'role' => 'seller',
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Auth/VerifyEmail')
                ->where('isSeller', true)
                ->where('userRole', 'seller')
        );
    }

    public function test_verification_notice_shows_buyer_content(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'role' => 'buyer',
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Auth/VerifyEmail')
                ->where('isSeller', false)
                ->where('userRole', 'buyer')
        );
    }

    public function test_seller_verification_email_contains_seller_content(): void
    {
        $user = User::factory()->create([
            'role' => 'seller',
        ]);

        $notification = new \App\Notifications\VerifyEmailNotification();
        $mailMessage = $notification->toMail($user);

        $this->assertStringContainsString('Start Selling on Musika Wedu', $mailMessage->subject);
        $this->assertStringContainsString('Welcome to Musika Wedu', $mailMessage->greeting);
        $this->assertStringContainsString('As a seller', $mailMessage->introLines[1]);
    }

    public function test_buyer_verification_email_contains_buyer_content(): void
    {
        $user = User::factory()->create([
            'role' => 'buyer',
        ]);

        $notification = new \App\Notifications\VerifyEmailNotification();
        $mailMessage = $notification->toMail($user);

        $this->assertStringContainsString('Verify Your Email - Musika Wedu', $mailMessage->subject);
        $this->assertStringContainsString('Welcome to Musika Wedu', $mailMessage->greeting);
        $this->assertStringContainsString('Verify Email Address', $mailMessage->actionText);
    }
}