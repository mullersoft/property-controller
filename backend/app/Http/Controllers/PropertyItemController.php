<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyItem;
use Illuminate\Http\Request;

class PropertyItemController extends Controller
{
    public function index()
    {
        $items = PropertyItem::with(['property', 'employee'])->get();
        return response()->json([
            'status' => 'success',
            'items' => $items
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'serial_number' => 'required|string|unique:property_items,serial_number',
            'status' => 'in:available,assigned,maintenance,retired',
        ]);

        $item = PropertyItem::create($validated);
        return response()->json(['status' => 'success', 'item' => $item]);
    }

    public function show($id)
    {
        $item = PropertyItem::with(['property', 'employee'])->findOrFail($id);
        return response()->json(['status' => 'success', 'item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'serial_number' => 'required|string|unique:property_items,serial_number,' . $id,
            'status' => 'in:available,assigned,maintenance,retired',
        ]);

        $item = PropertyItem::findOrFail($id);
        $item->update($validated);

        return response()->json(['status' => 'success', 'item' => $item]);
    }

    // public function updateStatus(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'status' => 'required|in:available,assigned,maintenance,retired',
    //         'employee_id' => 'nullable|exists:employees,id'
    //     ]);

    //     $item = PropertyItem::findOrFail($id);
    //     $item->update($validated);

    //     return response()->json(['status' => 'success', 'item' => $item]);
    // }

    //  ADD THIS METHOD
    // public function checkSerial($serial)
    // {
    //     try {
    //         $exists = PropertyItem::where('serial_number', $serial)->exists();
    //         return response()->json(['exists' => $exists]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Failed to check serial number',
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
