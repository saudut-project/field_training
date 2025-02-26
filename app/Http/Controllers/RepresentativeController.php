<?php

namespace App\Http\Controllers;

use App\Models\Representative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RepresentativeController extends Controller
{
    public function index()
    {
        $representatives = Representative::all();
        return response()->json($representatives);
    }

    public function updateMyPassword(Request $request)
    {

        $chairperson = Representative::where('representative_id','=', auth()->user()->representative_id)->first();
        $validatedData = $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:8',
            'email' => 'sometimes'
        ]);
    
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }
    
        $chairperson->update($validatedData);
    
        return response()->json($chairperson, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:representatives',
        ]);

        $representative = Representative::create($validatedData);

        return response()->json($representative, 201);
    }

    public function show(Representative $representative)
    {
        return $representative;
    }

    public function update(Request $request, Representative $representative)
    {
        $validatedData = $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:8',
            'email' => 'sometimes|required|string|email|max:255|unique:representatives,email,' . $representative->id,
        ]);

        $representative->update($validatedData);

        return response()->json($representative, 200);
    }

    public function destroy(Representative $representative)
    {
        $representative->delete();

        return response()->json(null, 204);
    }
}
