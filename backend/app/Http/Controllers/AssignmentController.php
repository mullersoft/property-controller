<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Property;
use App\Models\PropertyItem;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with('employee', 'property')->get();

        return response()->json([
            'status' => 'success',
            'result' => $assignments->count(),
            'assignments' => $assignments
        ], 200);
    }

    public function show($id)
    {
        $assignment = Assignment::with('employee', 'property')->find($id);

        if (!$assignment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Assignment not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'assignment' => $assignment
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'property_ids'  => 'required|array|min:1',
            'property_ids.*'=> 'exists:properties,id',
            'assigned_date' => 'required|date',
            'return_date'   => 'nullable|date|after:assigned_date',
        ]);

        $assignments = [];

        foreach ($validated['property_ids'] as $propertyId) {
            $property = Property::find($propertyId);

            if (!$property) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Property with ID $propertyId not found."
                ], 404);
            }

            //  Find one available item for this property
            $availableItem = PropertyItem::where('property_id', $propertyId)
                ->where('status', 'available')
                ->first();

            if (!$availableItem) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "No available items found for property '{$property->name}'."
                ], 400);
            }

            //  Update the item status to 'assigned'
            $availableItem->update([
                'status' => 'assigned',
                'employee_id' => $validated['employee_id'],
            ]);

            //  Create an assignment record
            $assignments[] = Assignment::create([
                'employee_id'   => $validated['employee_id'],
                'property_id'   => $propertyId,
                'assigned_date' => $validated['assigned_date'],
                'return_date'   => $validated['return_date'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Assignments created successfully!',
            'data' => $assignments
        ], 201);
    }
    public function update(Request $request, $id)
{
    $assignment = Assignment::find($id);

    if (!$assignment) {
        return response()->json([
            'status' => 'error',
            'message' => 'Assignment not found.'
        ], 404);
    }

    $validated = $request->validate([
        'employee_id'   => 'sometimes|exists:employees,id',
        'assigned_date' => 'sometimes|nullable|date',
        'return_date'   => 'sometimes|date|after_or_equal:assigned_date',
    ]);

    // Update the assignment record
    $assignment->update($validated);

    return response()->json([
        'status' => 'success',
        'message' => 'Assignment updated successfully.',
        'data' => $assignment
    ], 200);
}

        public function destroy($id)
    {
        $assignment = Assignment::find($id);
        if (!$assignment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Assignment not fond'
            ]);
        }
        $assignment->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Assignment deleted successfully'
        ],204);
    }
    public function return(Request $request, $id)
{
    // Find the assignment
    $assignment = Assignment::find($id);

    if (!$assignment) {
        return response()->json([
            'status' => 'error',
            'message' => 'Assignment not found'
        ], 404);
    }

    // Find the property item assigned to this employee
    $propertyItem = PropertyItem::where('property_id', $assignment->property_id)
        ->where('employee_id', $assignment->employee_id)
        ->where('status', 'assigned')
        ->first();

    if (!$propertyItem) {
        return response()->json([
            'status' => 'error',
            'message' => 'No assigned property item found for this employee'
        ], 404);
    }

    // Update property item status to 'available' and remove employee assignment
    $propertyItem->update([
        'status' => 'available',
        'employee_id' => null,
    ]);

    // Optionally, update assignment to mark it as returned
    $assignment->update([
        'return_date' => now(), // mark returned today
        // or add a 'returned' boolean column if needed
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Property returned successfully',
        'data' => $assignment
    ], 200);
}


}
