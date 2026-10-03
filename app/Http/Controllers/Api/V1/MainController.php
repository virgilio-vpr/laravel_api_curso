<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MovementResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Movement;
use App\Models\Product;
use App\Services\ApiResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function status()
    {
        return ApiResponse::success([
            'currentStatusString' => 'API is running',
            'serverDate' => now()->toDateTimeString(),
            'serverTime' => now()->toTimeString(),
            'serverTimestamp' => now()->timestamp,
            'serverTimezone' => now()->timezoneName,
            'apiVersion' => 'v1',
        ]);
    }

    public function listCategories()
    {
        $categories = Category::all(['id', 'name', 'description']); 

        return ApiResponse::success([
            // 'categories' => $categories,

            // A linha a seguir tem a mesma função da de baixo
            // 'categories' => CategoryResource::collection($categories),
            'categories' => $categories->toResourceCollection(CategoryResource::class),
            'totalCategories' => $categories->count(),
        ]);
    }

    public function listProducts()
    {
        $products = Product::with('category')->get();
  
        return ApiResponse::success([
            'products' => ProductResource::collection($products),
            // 'products' => $products->toResourceCollection(ProductResource::class),
            'totalProducts' => $products->count(),
        ]);
    }

    public function listMovements()
    {
        $movements = Movement::with('product.category')->get();

        // for testing with empty collection
        // $movements = collect();

        return ApiResponse::success([
            'movements' => MovementResource::collection($movements),
            'totalMovements' => $movements->count(),
        ]);
    }

    public function getCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return ApiResponse::error("Category with ID {$id} not found.", 404);
        }

        return ApiResponse::success([
            'category' => new CategoryResource($category)
        ]);

    }

    public function getProduct($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return ApiResponse::error("Product with ID {$id} not found.", 404);
        }

        return ApiResponse::success([
            'product' => new ProductResource($product)
        ]);
    }
}
