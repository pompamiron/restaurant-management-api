<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('MyTokenName')->accessToken;

            return response([
                'message' => 'Login successful',
                'user' => $user,
                'access_token' => $token,
            ]);
        }

        return response(['message' => 'Invalid credentials'], 422);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->token()->revoke();
            return response(['message' => 'Successfully logged out']);
        }

        return response(['message' => 'User not authenticated'], 401);
    }
}
