<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\models\Employee;
use App\Models\property;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignment = Assignment::with('employee', 'property')->get();
        return response()->json([
            'status' => 'success',
            'result' => $assignment->count(),
            'assignment' => $assignment
        ],200);
    }

    public function show($id)
    {
        $assignment = Assignment::with('employee', 'property')->find($id);
        if (!$assignment) {
            return response()->json([
                'status' => 'error',
                'message' => 'assignment not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'assignment' => $assignment
        ],200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'property_id' => 'required|exists:properties,id',
            'assigned_date' => 'required|date',
            'return_date' => 'required|date'
        ]);
        $assignment = Assignment::create($validated);
        return response()->json([
            'status' => 'success',
            'assignment' => $assignment
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $assignment = Assignment::find($id);
        if (!$assignment) {
            return response()->json([
                'status' => 'error',
                'message' => 'assignment not found'
            ], 404);
        };
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'property_id' => 'required|exists:properties,id',
            'assigned_date' => 'required|date',
            'return_date' => 'required|date'
        ]);
        $assignment->update($validated);

        return response()->json([
            'status' => 'success',
            'assignment' => $assignment
        ], 200);
    }

    public function destroy($id)
    {
        $assignment = Assignment::find($id);
        if (!$assignment) {
            return response()->json([
                'status' => 'error',
                'message' => 'assignment not found'
            ], 404);
        }
        $assignment->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'assignment deleted successfully'
        ], 204);
    }
}
