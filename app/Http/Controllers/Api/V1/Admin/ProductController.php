<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Remove fraudulent or inappropriate product listing.
     */
    public function destroy(Product $product): JsonResponse
    {
        // Delete associated photos from storage
        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product listing removed successfully.',
        ]);
    }
}
