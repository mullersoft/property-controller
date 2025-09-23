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
        $getCategories  = Category::with('property')->get();

        // return response()->json($getCategories);
        return response()->json([
            'status' => 'success',
            'result' => $getCategories ->count(),
           'category' => $getCategories
        ], 200);
    }

    public function show($id)
    {
        // $getCategoryById  = Category::findOrFail($id);
        $getCategoryById  = Category::with('property')->find($id);

        if (!$getCategoryById) {
        return response()->json([
            'status' => 'error',
            'message' => 'Category not found'
        ], 404);
    }
        // return response()->json($getCategoryById );
        return response()->json([
            'status' => 'success',
            'numOfProperties' => $getCategoryById ->property->count(),
            'category'  => $getCategoryById
        ]);
    }

    public function store(Request $request)
    {
        $createCategory = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        // return response()->json($createCategory);
        $category = Category::create($createCategory);
        return response()->json([
            'status' => 'success',
            'category' => $category
        ]);
    }
public function destroy($id){
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                'status' => 'error',
                'message' => 'Category not fond'
            ]);
        }
        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully'
        ]);
}
}
