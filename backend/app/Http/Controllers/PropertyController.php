<?php

namespace App\Http\Controllers;

use App\models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(){
        // $property = Property::all();
        $property = Property::with('category')->get();
        // dump($property);
        // return response()->json($property);
        return response()->json([
            'status' => 'success',
            'result' => $property->count(),
            'property' => $property
        ]);
    }
    public function show($id){
        $property = Property::with('category')->find($id);
        if(!$property){
            return response()->json([
                'status' => 'error',
                'message' => 'property not found'
            ]);
        }
        // return response()->json($property);
        return response()->json([
            'status' => 'success',
            'property' => $property
        ]);
    }
    public function store(Request $request){
        $createProperty = $request->validate([
            'name' => 'required|string|max:225',
            'category_id' => 'required|exists:categories,id',
            'purchase_date'=>'required|date',
            'purchase_cost'=>'required|numeric',
            'status'=>'required|string|in:available,assigned,maintenance','retired',
            'serial_number'=>'required|string|unique:properties,serial_number',
            'model_number'=>'required|string',
            'manufacturer'=>'required|string',
            'current_value'=>'required|numeric'

        ]);
        $property = Property::create($createProperty);
        return response()->json([
            'status' => 'success',
            'data' => $property
        ]);
    }
    public function destroy($id){
        $property = Property::find($id);
        if(!$property){
            return response()->json([
                'status' => 'error',
                'message' => 'Property not fond'
            ]);
        }
        $property->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Property deleted successfully'
        ]);
}
}
