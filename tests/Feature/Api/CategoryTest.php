<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest can view all categories.
     */
    public function test_guest_can_view_all_categories(): void
    {
        Category::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                    ],
                ],
            ]);
    }

    /**
     * Test guest can view single category.
     */
    public function test_guest_can_view_single_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/v1/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                ],
            ]);
    }

    /**
     * Test admin can create category.
     */
    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/categories', [
                'name' => 'New Category',
                'description' => 'Category description',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                ],
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'New Category',
        ]);
    }

    /**
     * Test seller cannot create category.
     */
    public function test_seller_cannot_create_category(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $token = $seller->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/categories', [
                'name' => 'New Category',
                'description' => 'Category description',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test admin can update category.
     */
    public function test_admin_can_update_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->putJson("/api/v1/admin/categories/{$category->id}", [
                'name' => 'Updated Category Name',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category Name',
        ]);
    }

    /**
     * Test admin can delete empty category.
     */
    public function test_admin_can_delete_empty_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->deleteJson("/api/v1/admin/categories/{$category->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * Test category name must be unique.
     */
    public function test_category_name_must_be_unique(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Category::factory()->create(['name' => 'Existing Category']);
        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/categories', [
                'name' => 'Existing Category',
                'description' => 'Another description',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}
