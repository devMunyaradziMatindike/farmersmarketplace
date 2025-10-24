<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest can view all products.
     */
    public function test_guest_can_view_all_products(): void
    {
        $category = Category::factory()->create();
        $seller = User::factory()->create(['role' => 'seller']);
        Product::factory()->count(5)->create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'status' => 'available',
        ]);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'location',
                        'status',
                        'category',
                        'seller',
                    ],
                ],
            ]);
    }

    /**
     * Test guest can view single product.
     */
    public function test_guest_can_view_single_product(): void
    {
        $category = Category::factory()->create();
        $seller = User::factory()->create(['role' => 'seller']);
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
        ]);

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'price',
                ],
            ]);
    }

    /**
     * Test products can be filtered by category.
     */
    public function test_products_can_be_filtered_by_category(): void
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $seller = User::factory()->create(['role' => 'seller']);

        Product::factory()->count(3)->create([
            'user_id' => $seller->id,
            'category_id' => $category1->id,
            'status' => 'available',
        ]);

        Product::factory()->count(2)->create([
            'user_id' => $seller->id,
            'category_id' => $category2->id,
            'status' => 'available',
        ]);

        $response = $this->getJson("/api/v1/products?category_id={$category1->id}");

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    /**
     * Test products can be searched by name.
     */
    public function test_products_can_be_searched_by_name(): void
    {
        $category = Category::factory()->create();
        $seller = User::factory()->create(['role' => 'seller']);

        Product::factory()->create([
            'name' => 'Fresh Maize',
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'status' => 'available',
        ]);

        Product::factory()->create([
            'name' => 'Organic Tomatoes',
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'status' => 'available',
        ]);

        $response = $this->getJson('/api/v1/products?name=maize');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Fresh Maize', $response->json('data.0.name'));
    }

    /**
     * Test products can be filtered by price range.
     */
    public function test_products_can_be_filtered_by_price_range(): void
    {
        $category = Category::factory()->create();
        $seller = User::factory()->create(['role' => 'seller']);

        Product::factory()->create([
            'price' => 10.00,
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'status' => 'available',
        ]);

        Product::factory()->create([
            'price' => 50.00,
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'status' => 'available',
        ]);

        Product::factory()->create([
            'price' => 100.00,
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'status' => 'available',
        ]);

        $response = $this->getJson('/api/v1/products?min_price=20&max_price=80');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    /**
     * Test seller can create product.
     */
    public function test_seller_can_create_product(): void
    {
        Storage::fake('public');

        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::factory()->create();
        $token = $seller->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/seller/products', [
                'category_id' => $category->id,
                'name' => 'Fresh Maize',
                'description' => 'Organic white maize',
                'price' => 45.50,
                'location' => 'Harare, Zimbabwe',
                'latitude' => -17.8252,
                'longitude' => 31.0335,
                'contact_details' => '+263771234567',
                'status' => 'available',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'price',
                ],
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Fresh Maize',
            'user_id' => $seller->id,
        ]);
    }

    /**
     * Test buyer cannot create product.
     */
    public function test_buyer_cannot_create_product(): void
    {
        $buyer = User::factory()->create(['role' => 'buyer']);
        $category = Category::factory()->create();
        $token = $buyer->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/v1/seller/products', [
                'category_id' => $category->id,
                'name' => 'Fresh Maize',
                'description' => 'Organic white maize',
                'price' => 45.50,
                'location' => 'Harare',
                'contact_details' => '+263771234567',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test seller can update their own product.
     */
    public function test_seller_can_update_their_own_product(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
        ]);

        $token = $seller->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->putJson("/api/v1/seller/products/{$product->id}", [
                'name' => 'Updated Product Name',
                'price' => 100.00,
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'price' => 100.00,
        ]);
    }

    /**
     * Test seller cannot update another seller's product.
     */
    public function test_seller_cannot_update_another_sellers_product(): void
    {
        $seller1 = User::factory()->create(['role' => 'seller']);
        $seller2 = User::factory()->create(['role' => 'seller']);
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $seller1->id,
            'category_id' => $category->id,
        ]);

        $token = $seller2->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->putJson("/api/v1/seller/products/{$product->id}", [
                'name' => 'Hacked Product',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test seller can delete their own product.
     */
    public function test_seller_can_delete_their_own_product(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
        ]);

        $token = $seller->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->deleteJson("/api/v1/seller/products/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    /**
     * Test seller can update product status.
     */
    public function test_seller_can_update_product_status(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'status' => 'available',
        ]);

        $token = $seller->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->patchJson("/api/v1/seller/products/{$product->id}/status", [
                'status' => 'sold_out',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => 'sold_out',
        ]);
    }

    /**
     * Test admin can delete any product.
     */
    public function test_admin_can_delete_any_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create(['role' => 'seller']);
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
        ]);

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->deleteJson("/api/v1/admin/products/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
