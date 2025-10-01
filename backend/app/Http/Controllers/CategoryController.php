<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        // $getCategories = Category::all();
        $getCategories = Category::with('property')->get();
if($getCategories->isEmpty()){
    return response()->json([
        'status' => 'error',
        'message' => 'No categories found'
    ], 404);
}
        // return response()->json($getCategories);
        return response()->json([
            'status' => 'success',
            'result' => $getCategories->count(),
            'category' => $getCategories
        ], 200);
    }

    public function show($id)
    {
        // $getCategoryById  = Category::findOrFail($id);
        $getCategoryById = Category::with('property')->find($id);

        if (!$getCategoryById) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }
        // return response()->json($getCategoryById );
        return response()->json([
            'status' => 'success',
            'numOfProperties' => $getCategoryById->property->count(),
            'category' => $getCategoryById
        ],200);
    }

    public function store(Request $request)
    {
        $createCategory = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string|max:255'
        ]);
        // return response()->json($createCategory);
        $category = Category::create($createCategory);
        return response()->json([
            'status' => 'success',
            'category' => $category
        ],201);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not fond'
            ]);
        }
        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully'
        ],204);
    }

    // update category
    public function update(Request $request, $id)
    {
        // Find the category by ID
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string|max:255'
        ]);

        // Update category
        $category->update($validated);

        return response()->json([
            'status' => 'success',
            'category' => $category
        ],200);
    }
}
