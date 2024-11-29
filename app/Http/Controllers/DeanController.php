<?php

namespace App\Http\Controllers;

use App\Models\Dean;
use Illuminate\Http\Request;

class DeanController extends Controller
{
    public function index()
    {
        return Dean::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:deans',
        ]);

        $dean = Dean::create($validatedData);

        return response()->json($dean, 201);
    }

    public function show(Dean $dean)
    {
        return $dean;
    }

    public function update(Request $request, Dean $dean)
    {
        $validatedData = $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:8',
            'email' => 'sometimes|required|string|email|max:255|unique:deans,email,' . $dean->id,
        ]);

        $dean->update($validatedData);

        return response()->json($dean, 200);
    }

    public function destroy(Dean $dean)
    {
        $dean->delete();

        return response()->json(null, 204);
    }
}
