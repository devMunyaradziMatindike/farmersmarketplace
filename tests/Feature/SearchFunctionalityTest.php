<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_suggestions_endpoint(): void
    {
        // Create test data
        $category = Category::factory()->create(['name' => 'Maize']);
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Fresh Maize',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // Test suggestions endpoint
        $response = $this->get('/api/v1/search/suggestions?q=maize');

        $response->assertStatus(200)
            ->assertJsonStructure(['suggestions'])
            ->assertJsonFragment(['suggestions' => ['Fresh Maize', 'Maize']]);
    }

    public function test_popular_searches_endpoint(): void
    {
        // Test popular searches endpoint
        $response = $this->get('/api/v1/search/popular');

        $response->assertStatus(200)
            ->assertJsonStructure(['popular']);
    }

    public function test_enhanced_search_with_filters(): void
    {
        // Create test data
        $category = Category::factory()->create(['name' => 'Vegetables']);
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Fresh Tomatoes',
            'price' => 50.00,
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // Test search with filters
        $response = $this->get('/api/v1/search?q=tomatoes&category=' . $category->id . '&min_price=40&max_price=60');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'products' => [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'price',
                            'category',
                            'user'
                        ]
                    ]
                ],
                'total',
                'filters'
            ]);
    }

    public function test_products_page_with_search(): void
    {
        // Create test data
        $category = Category::factory()->create(['name' => 'Crops']);
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Wheat Grain',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // Test products page with search
        $response = $this->get('/products?q=wheat');

        $response->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Products/Index')
                ->has('products.data')
                ->has('categories')
                ->has('locations')
                ->has('filters')
            );
    }
}