<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $fields = $request->validate([
            'userName' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('userName', $fields['userName'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ] , 200);
    }

    public function register(Request $request)
    {

        $fields = $request->validate([
            'userName' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|string',
            'employe_id' => 'required',
        ]);        

        $user = User::create($fields);
        return response()->json([
            'user' => $user,
            'message' => 'User created successfully',
          ],201
        );
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out',
        ]);
    }

}
