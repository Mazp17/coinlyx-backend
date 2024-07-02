<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();

        return response()->json([
            'user' => $user,
            'token' => $user->api_token,
        ], 200);
    }

    public function me(Request $request)
    {
        return new UserResource(User::with('account')->find(auth()->id()));
    }
}
