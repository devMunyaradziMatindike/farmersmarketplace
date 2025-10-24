<?php

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\StoreProductRequest;
use App\Http\Requests\Api\V1\Product\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the seller's products.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::with(['category', 'user', 'photos'])
            ->where('user_id', $request->user()->id)
            ->orderBy('date_listed', 'desc')
            ->paginate(15);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'contact_details' => $request->contact_details,
            'status' => $request->status ?? 'available',
        ]);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('products', 'public');

                ProductPhoto::create([
                    'product_id' => $product->id,
                    'photo_path' => $path,
                    'is_primary' => $index === 0,
                    'order' => $index,
                ]);
            }
        }

        $product->load(['category', 'user', 'photos']);

        return response()->json([
            'message' => 'Product created successfully.',
            'data' => new ProductResource($product),
        ], 201);
    }

    /**
     * Display the specified product.
     */
    public function show(Request $request, Product $product): JsonResponse
    {
        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized. This product does not belong to you.',
            ], 403);
        }

        $product->load(['category', 'user', 'photos']);

        return response()->json([
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized. You cannot update this product.',
            ], 403);
        }

        $product->update($request->validated());
        $product->load(['category', 'user', 'photos']);

        return response()->json([
            'message' => 'Product updated successfully.',
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Request $request, Product $product): JsonResponse
    {
        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized. You cannot delete this product.',
            ], 403);
        }

        // Delete associated photos from storage
        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }

    /**
     * Update the product status.
     */
    public function updateStatus(Request $request, Product $product): JsonResponse
    {
        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized. You cannot update this product.',
            ], 403);
        }

        $request->validate([
            'status' => ['required', 'in:available,sold_out,soon_to_be_available,delisted'],
        ]);

        $product->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Product status updated successfully.',
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Add photos to the product.
     */
    public function addPhotos(Request $request, Product $product): JsonResponse
    {
        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized. You cannot add photos to this product.',
            ], 403);
        }

        $request->validate([
            'photos' => ['required', 'array', 'max:10'],
            'photos.*' => ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
        ]);

        $currentPhotoCount = $product->photos()->count();
        $maxOrder = $product->photos()->max('order') ?? -1;

        if ($currentPhotoCount + count($request->file('photos')) > 10) {
            return response()->json([
                'message' => 'Maximum of 10 photos per product allowed.',
            ], 422);
        }

        foreach ($request->file('photos') as $index => $photo) {
            $path = $photo->store('products', 'public');

            ProductPhoto::create([
                'product_id' => $product->id,
                'photo_path' => $path,
                'is_primary' => $currentPhotoCount === 0 && $index === 0,
                'order' => $maxOrder + $index + 1,
            ]);
        }

        $product->load('photos');

        return response()->json([
            'message' => 'Photos added successfully.',
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Delete a photo from the product.
     */
    public function deletePhoto(Request $request, Product $product, ProductPhoto $photo): JsonResponse
    {
        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        // Ensure the photo belongs to the product
        if ($photo->product_id !== $product->id) {
            return response()->json([
                'message' => 'Photo does not belong to this product.',
            ], 404);
        }

        // Delete from storage
        Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        return response()->json([
            'message' => 'Photo deleted successfully.',
        ]);
    }
}
