<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Store;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $query = Category::withCount('products');

        // if ($request->has('search')) {
        //     $search = $request->search;
        //     $query->where(function($q) use ($search) {
        //         $q->where('name', 'LIKE', "%{$search}%")
        //         ->orWhere('jumlah_produk', '>=', $search );
        //     });
        // }

        $query = Category::withCount('products');
        if ($request->filled('search')) {
            $search = $request->search;

            if (is_numeric($search)) {
                $query->having('products_count', '<=', (int) $search);
            } else {
                $query->where('name', 'LIKE', "%{$search}%");
            }
        }

        $categories = $query->latest()->paginate(10);
        return CategoryResource::collection($categories);
        // $categories = Category::withCount('products')->get();
        // return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category->loadCount('products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }
}
