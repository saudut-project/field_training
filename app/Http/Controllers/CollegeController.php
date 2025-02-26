<?php

namespace App\Http\Controllers;

use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colleges = College::all();
        return response()->json($colleges);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('colleges.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dean_id' => 'required',
        ]);

        // تخزين على قاعدة البينانات " colleges "
        $college = College::create($validated);

        return response()->json($college, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $college = College::find($id);
        return response()->json($college);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(College $college)
    {
        return view('colleges.edit', compact('college'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $college = College::find($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dean_id' => 'required',
        ]);

        $college->update($validated);

        return response()->json($college);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(College $college)
    {
        $college->delete();

        return response()->json(null, 204);
    }
}