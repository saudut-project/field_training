<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return Department::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'college_id' => 'required|exists:colleges,college_id',
            'chairperson_id' => 'required|exists:chairpersons,chairperson_id',
        ]);

        $department = Department::create($validatedData);

        return response()->json($department, 201);
    }

    public function show(Department $department)
    {
        return $department;
    }

    public function update(Request $request, Department $department)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'college_id' => 'sometimes|required|exists:colleges,college_id',
            'chairperson_id' => 'sometimes|required|exists:chairpersons,chairperson_id',
        ]);

        $department->update($validatedData);

        return response()->json($department, 200);
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json(null, 204);
    }
}
