<?php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

    class StudentController extends Controller
{
public function register(Request $request)
{
    Log::info($request->all());
    $validated = $request->validate([
        'username' => 'required|string|max:255',
        'password' => 'required|string|min:8',
        'email' => 'required|string|email|max:255|unique:students',
        'college_id' => 'required|exists:colleges,college_id',
        'department_id' => 'required|exists:departments,department_id',
    ]);

    $student = Student::create([
        'username' => $validated['username'],
        'password' => Hash::make($validated['password']),
        'email' => $validated['email'],
        'college_id' => $validated['college_id'],
        'department_id' => $validated['department_id'],
    ]);

    return response()->json($student, 201);
}
}