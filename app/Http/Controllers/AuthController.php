<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
    
            // Generate a new access token for the user.
            $token = $user->createToken('MyTokenName')->accessToken;
    
            return response(['user' => $user, 'access_token' => $token]);
        }
    
        return response(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(['message' => 'Successfully logged out']);
    }

}
