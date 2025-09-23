<?php

namespace App\Http\Controllers;

use App\models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        // $property = Property::all();
        $property = Property::with('category')->get();
        // dump($property);
        return response()->json($property);
    }

    public function show($id)
    {
        $property = Property::with('category')->findOrFail($id);
        return response()->json($property);
    }
}
