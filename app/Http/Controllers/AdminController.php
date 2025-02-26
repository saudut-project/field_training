<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;

class AdminController extends Controller
{
    // ارجاع قائمة الادمنز بالتطبيق
    public function index()
    {
        return Admin::all();
    }

    // تخزين ادمن جديد
    public function store(Request $request)
    {
// التحقق من المدخلات
        $validatedData = $request->validate([
            'username' => 'required|string|max:120',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:admins',
        ]);
// الادخال في قاعدة البيانات الجدول -ادمن
        $admin = Admin::create($validatedData);

        return response()->json($admin, 201);
    }

    // عرض معلومات الادمن.
    public function show(Admin $admin)
    {
        return $admin;
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        Log::info($request->all());
        $admin = Admin::findOrFail($id); // Fetch the Admin by ID

        $validatedData = $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:8',
            'email' => 'sometimes',
        ]);

        // Hash the password if it is present in the request
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

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
