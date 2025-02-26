<?php

namespace App\Http\Controllers;

use App\Models\Dean;
use App\Models\Chenclor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class DeanController extends Controller
{
    public function index()
    {
        $deans = Dean::all();
        return response()->json($deans);
    
    }    public function indexchencelor()
    {
        $deans = Chenclor::all();
        return response()->json($deans);
    
    }

    public function store(Request $request)
    {
        Log::info($request->all());
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:deans',
        ]);
        $validatedData['password'] = bcrypt($validatedData['password']);

        $dean = Dean::create($validatedData);

        return response()->json($dean, 201);
    }
    public function storeChencelor(Request $request)
    {
        Log::info($request->all());
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:deans',
        ]);
        $validatedData['password'] = bcrypt($validatedData['password']);

        $dean = Chenclor::create($validatedData);

        return response()->json($dean, 201);
    }
    public function show($id)
    {
        $dean = Dean::find($id);
        return response()->json($dean);
    }   public function showchencelor($id)
    {
        $dean = Chenclor::find($id);
        return response()->json($dean);
    }

    public function myData()
    {
        $dean = Dean::find(auth()->user()->dean_id);
        return response()->json($dean);
    }

    public function update(Request $request, $id)
    {
        Log::info($request->all());
        $admin = Dean::findOrFail($id); // Fetch the Admin by ID

        $validatedData = $request->validate([
            'username' => 'sometimes|required|string|max:255',
          //  'password' => 'sometimes|required|string|min:8',
            'email' => 'sometimes',
        ]);

        // Hash the password if it is present in the request
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $admin->update($validatedData);

        return response()->json($admin, 200);
    }

    public function destroy(Dean $dean)
    {
        $dean->delete();

        return response()->json(null, 204);
    }
}
