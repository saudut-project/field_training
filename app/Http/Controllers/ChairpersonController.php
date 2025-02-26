<?php

namespace App\Http\Controllers;

use App\Models\Chairperson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
class ChairpersonController extends Controller
{
    public function index()
    {
        $chairpersons = Chairperson::all();
        return response()->json($chairpersons);
    
    }

    public function updateMyPassword(Request $request)
    {
        $chairperson = Chairperson::where('chairperson_id','=', auth()->user()->chairperson_id)->first();
    
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

    public function myData()
    {
        $chairperson = Chairperson::find(auth()->user()->chairperson_id);
        return response()->json($chairperson);
    }
    public function store(Request $request)
    {
        Log::info($request->all());
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:chairpersons',
        ]);

            $chairperson = Chairperson::create($validatedData);

        return response()->json($chairperson, 201);
    }

    public function show($id)
    {
        $chairperson = Chairperson::find($id);
        return response()->json($chairperson);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:8',
            'email' => 'sometimes|required|string|email|max:255'
        ]);

        $chairperson = Chairperson::findOrFail($id);
        $chairperson->update($validatedData);

            return response()->json($chairperson, 200);
    }

    public function destroy(Chairperson $chairperson)
    {
            $chairperson->delete();

        return response()->json(null, 204);
    }
}
