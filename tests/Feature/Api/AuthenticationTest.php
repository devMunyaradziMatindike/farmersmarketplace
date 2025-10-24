<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can register with phone number.
     */
    public function test_user_can_register_with_phone_number(): void
    {
        $response = $this->postJson('/api/v1/auth/register/phone', [
            'phone_number' => '+263771234567',
            'name' => 'Test User',
            'password' => 'password123',
            'role' => 'seller',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'phone_number',
                    'role',
                    'auth_method',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'phone_number' => '+263771234567',
            'name' => 'Test User',
            'role' => 'seller',
        ]);
    }

    /**
     * Test user cannot register with duplicate phone number.
     */
    public function test_user_cannot_register_with_duplicate_phone(): void
    {
        User::factory()->create(['phone_number' => '+263771234567']);

        $response = $this->postJson('/api/v1/auth/register/phone', [
            'phone_number' => '+263771234567',
            'name' => 'Test User',
            'password' => 'password123',
            'role' => 'seller',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone_number']);
    }

    /**
     * Test user can login with phone number.
     */
    public function test_user_can_login_with_phone(): void
    {
        $user = User::factory()->create([
            'phone_number' => '+263771234567',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'phone_number' => '+263771234567',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user',
                'token',
            ]);
    }

    /**
     * Test user can login with email.
     */
    public function test_user_can_login_with_email(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user',
                'token',
            ]);
    }

    /**
     * Test login fails with invalid credentials.
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'phone_number' => '+263771234567',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'phone_number' => '+263771234567',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials.',
            ]);
    }

    /**
     * Test authenticated user can logout.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logout successful.',
            ]);
    }

    /**
     * Test OTP verification with valid OTP.
     */
    public function test_otp_verification_with_valid_otp(): void
    {
        $user = User::factory()->create([
            'phone_number' => '+263771234567',
            'otp' => '123456',
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'phone_number' => '+263771234567',
            'otp' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user',
                'token',
            ]);

        // OTP should be cleared after verification
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'otp' => null,
        ]);
    }

    /**
     * Test OTP verification fails with invalid OTP.
     */
    public function test_otp_verification_fails_with_invalid_otp(): void
    {
        User::factory()->create([
            'phone_number' => '+263771234567',
            'otp' => '123456',
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'phone_number' => '+263771234567',
            'otp' => '999999',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid or expired OTP.',
            ]);
    }

    /**
     * Test OTP verification fails with expired OTP.
     */
    public function test_otp_verification_fails_with_expired_otp(): void
    {
        User::factory()->create([
            'phone_number' => '+263771234567',
            'otp' => '123456',
            'otp_expires_at' => now()->subMinutes(1),
        ]);

        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'phone_number' => '+263771234567',
            'otp' => '123456',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid or expired OTP.',
            ]);
    }
}
