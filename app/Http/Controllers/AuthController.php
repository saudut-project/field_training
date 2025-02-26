<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use App\Models\Dean;
use App\Models\Chairperson;
use App\Models\Representative;
use App\Models\Student;
use App\Models\Chenclor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'user_type' => 'required|string', // e.g., admin, dean, chairperson, student, representative.
        ]);
    
        $userModel = match ($request->user_type) {
            'admin' => Admin::class,
            'dean' => Dean::class,
            'chairperson' => Chairperson::class,
            'representative' => Representative::class,
            'student' => Student::class,
            'chenclor' => Chenclor::class,
            default => null,
        };
    
        Log::info($userModel);
        Log::info($request->all());
        if ($userModel) {

            $user = $userModel::where('username', $request->username)->first();
    
            if ($user && Hash::check($request->password, $user->password)) {
                // Ensure $user is an instance of the correct model
                if ($user && Hash::check($request->password, $user->password)) {
                    // Create and return the token
                    $token = $user->createToken('mobile-token')->plainTextToken;
        
                    return response()->json([
                        'token' => $token,
                        'user' => $user,
                    ], 200);
                }
            }
        }
    
        return response()->json(['message' => 'Invalid credentials'], 401);
    }



      

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
