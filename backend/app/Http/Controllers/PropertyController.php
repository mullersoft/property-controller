<?php

namespace App\Http\Controllers;

use App\models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(){
        // $property = Property::all();
        $property = Property::with('category','assignment')->get();
        // dump($property);
        // return response()->json($property);
        return response()->json([
            'status' => 'success',
            'result' => $property->count(),
            'property' => $property
        ],200);
    }
    public function show($id){
        $property = Property::with('category','assignment')->find($id);
        if(!$property){
            return response()->json([
                'status' => 'error',
                'message' => 'property not found'
            ],404);
        }
        // return response()->json($property);
        return response()->json([
            'status' => 'success',
            'property' => $property
        ],200);
    }


    public function store(Request $request)
{
    $createProperty = $request->validate([
        'name' => 'required|string|max:225',
        'category_id' => 'required|exists:categories,id',
        'purchase_date' => 'required|date',
        'purchase_cost' => 'required|numeric',
        // 'status' => 'required|string|in:available,assigned,maintenance,retired',
        // 'serial_number' => 'nullable|string|unique:properties,serial_number',
        'model_number' => 'required|string',
        'manufacturer' => 'required|string',
        // 'current_value' => 'required|numeric',
        // 'quantity' => 'required|integer|min:1',
    ]);

    // $createProperty['available_quantity'] = $createProperty['quantity'];

    $property = Property::create($createProperty);

    return response()->json([
        'status' => 'success',
        'data' => $property
    ], 201);
}

public function update(Request $request, $id)
{
    $property = Property::find($id);

    if (!$property) {
        return response()->json(['status' => 'error', 'message' => 'Property not found'], 404);
    }

    $validated = $request->validate([
        'name' => 'sometimes|required|string|max:225',
        'category_id' => 'sometimes|required|exists:categories,id',
        'purchase_date' => 'sometimes|required|date',
        'purchase_cost' => 'sometimes|required|numeric',

        'model_number' => 'sometimes|required|string',
        'manufacturer' => 'sometimes|required|string',
    
    ]);

    // Automatically mark property as out_of_stock if none available
    if (isset($validated['available_quantity']) && $validated['available_quantity'] <= 0) {
        $validated['status'] = 'out_of_stock';
    }

    $property->update($validated);

    return response()->json(['status' => 'success', 'Property' => $property], 200);
}
    public function destroy($id){

        $property = Property::find($id);
        if(!$property){
            return response()->json([
                'status' => 'error',
                'message' => 'Property not fond'
            ],404);
        }
        $property->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Property deleted successfully'
        ],204);
}


}
