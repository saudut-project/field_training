<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function index()
    {
        return Institution::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'representative_id' => 'required|exists:representatives,representative_id',
        ]);

        $institution = Institution::create($validatedData);

        return response()->json($institution, 201);
    }

    public function show(Institution $institution)
    {
        return $institution;
    }

    public function update(Request $request, Institution $institution)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'representative_id' => 'sometimes|required|exists:representatives,representative_id',
        ]);

        $institution->update($validatedData);

        return response()->json($institution, 200);
    }

    public function destroy(Institution $institution)
    {
        $institution->delete();

        return response()->json(null, 204);
    }
}
