<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use App\Models\Representative;
use Illuminate\Support\Facades\Log;
class InstitutionController extends Controller
{
    public function index()
    {
        $institutions = Institution::all();
        return response()->json($institutions);
    }

    public function myInstitutions()
    {
        $institutions = Institution::where('representative_id', auth()->user()->representative_id)->get();
        return response()->json($institutions);
    }

    public function showInstitution($id)
{
    $institution = Institution::with('representative')->find($id);

    if (!$institution) {
        return response()->json(['error' => 'Institution not found'], 404);
    }

    return response()->json([
        'institution' => [
            'name' => $institution->name,
            'address' => $institution->address,
        ],
        'representative' => [
            'username' => $institution->representative->username,
            'email' => $institution->representative->email,
        ]
    ], 200);
}
public function myData()
{
    $chairperson = Representative::where('representative_id','=',auth()->user()->representative_id)->first();
    return response()->json($chairperson);
}
public function updateInstitution(Request $request, $id)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'name' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'representative.username' => 'nullable|string|max:255',
        'representative.password' => 'nullable|string|min:8',
        'representative.email' => 'nullable|string|email|unique:representatives,email,' . $id . ',representative_id',
    ]);

    // Find the institution
    $institution = Institution::with('representative')->find($id);

    if (!$institution) {
        return response()->json(['error' => 'Institution not found'], 404);
    }

    // Update the institution details
    if (isset($validatedData['name'])) {
        $institution->name = $validatedData['name'];
    }

    if (isset($validatedData['address'])) {
        $institution->address = $validatedData['address'];
    }

    $institution->save();

    // Update the representative details if provided
    $representative = $institution->representative;

    if ($representative) {
        if (isset($validatedData['representative']['username'])) {
            $representative->username = $validatedData['representative']['username'];
        }

        if (isset($validatedData['representative']['password'])) {
            $representative->password = bcrypt($validatedData['representative']['password']); // Hash password
        }

        if (isset($validatedData['representative']['email'])) {
            $representative->email = $validatedData['representative']['email'];
        }

        $representative->save();
    }

    return response()->json([
        'message' => 'Institution and representative updated successfully',
        'institution' => $institution,
        'representative' => $representative
    ], 200);
}


    public function storeInstitution(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'representative_id' => 'nullable|exists:representatives,representative_id',
            'representative.username' => 'required_without:representative_id|string|max:255',
            'representative.password' => 'required_without:representative_id|string|min:8',
            'representative.email' => 'required_without:representative_id|string|email|unique:representatives,email',
        ]);
    
        $representativeId = $validatedData['representative_id'];
    
        // If no representative_id is provided, create a new representative
        if (!$representativeId) {
            $representative = new Representative();
            $representative->username = $validatedData['representative']['username'];
            $representative->password = bcrypt($validatedData['representative']['password']); // Hash the password
            $representative->email = $validatedData['representative']['email'];
            $representative->save();
    
            $representativeId = $representative->representative_id; // Get the new representative's ID
        }
    
        // Create the institution
        $institution = new Institution();
        $institution->name = $validatedData['name'];
        $institution->address = $validatedData['address'];
        $institution->representative_id = $representativeId;
        $institution->save();
    
        return response()->json(['message' => 'Institution and representative processed successfully'], 201);
    }
    

    public function show(Institution $institution)
    {
        return $institution;
    }

    public function updateMyInstitution(Request $request)
    {
        Log::info($request->all());
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
        ]);
        $institution = Institution::where('representative_id', auth()->user()->representative_id)->first();
  

        $institution->update($validatedData);

        return response()->json($institution, 200);
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
