<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        // $category = Category::all();
        $getAll = Category::with('property')->get();

        // return response()->json($category);
        return response()->json([
            'status' => 'success',
            'result' => $getAll->count(),
            'data' => ['category' => $getAll]
        ], 200);
    }

    public function show($id)
    {
        // $category = Category::findOrFail($id);
        $getOne = Category::with('property')->findOrFail($id);
        // return response()->json($category);
        return response()->json([
            'status' => 'success',
            'numOfProperties' => $getOne->property->count(),
            'result' => 1,
            'data' => ['category' => $getOne]
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        return response()->json($validatedData);
    }
}
