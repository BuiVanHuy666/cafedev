<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $categories = Cache::remember('public_categories', 86400, function () {
            return CategoryResource::collection(Category::active()->ordered()->get())->resolve();
        });

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $categories,
        ]);
    }
}
