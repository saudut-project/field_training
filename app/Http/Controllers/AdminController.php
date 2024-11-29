<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return Admin::all();
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:admins',
        ]);

        $admin = Admin::create($validatedData);

        return response()->json($admin, 201);
    }

    // Display the specified resource.
    public function show(Admin $admin)
    {
        return $admin;
    }

    // Update the specified resource in storage.
    public function update(Request $request, Admin $admin)
    {
        $validatedData = $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:8',
            'email' => 'sometimes|required|string|email|max:255|unique:admins,email,' . $admin->id,
        ]);

        $admin->update($validatedData);

        return response()->json($admin, 200);
    }

    // Remove the specified resource from storage.
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return response()->json(null, 204);
    }
}
