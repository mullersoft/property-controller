<?php

namespace App\Http\Controllers;

use App\models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // index

    public function index()
    {
        $employee = Employee::with('assignment')->get();
        return response()->json([
            'status' => 'success',
            'result' => $employee->count(),
            'employee' => $employee
        ],200);
    }

    // show

    public function show($id)
    {
        $employee = Employee::with('assignment')->find($id);
        if (!$employee) {
            return response()->json([
                'return' => 'error',
                'message' => 'employee not found'
            ],404);
        }
        return response()->json([
            'status' => 'success',
            'employee' => $employee
        ],200);
    }

    // store

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:225',
            'department' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|unique:employees,phone',
        ]);
        $employee = Employee::create($validated);
        return response()->json([
            'status' => 'success',
            'employee' => $employee
        ],201);
    }

    // update
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'employee not found',
            ],404);
        };
        $employee->update($validated = $request->validate([
            'name' => 'sometimes|required|string|max:225',
            'email' => 'sometimes|required|email|unique:employees,email,' . $id,
            'phone' => 'sometimes|required|string|unique:employees,phone,' . $id,
            'department' => 'sometimes|required|string'
        ]));
        return response()->json([
            'status' => 'success',
            'employee' => $employee
        ],200);
    }

    // delete
    public function destroy($id)
    {
        $employee = Employee::find(id: $id);
        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'employee not found'
            ],404);
        }
        $employee->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'employee deleted successfully'
        ],204);
    }
}
