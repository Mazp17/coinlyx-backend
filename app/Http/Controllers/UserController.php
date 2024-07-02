<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function index(Request $request)
    {
        return new UserCollection(User::where('role', 'user')->latest()->get());
    }

    public function show(Request $request, $id)
    {
        $user = \App\Models\User::find($id);

        if(!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'user' => $user,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        dd($request);
        $user = User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => Str::random(60),
            'role' => 'user',
        ]);

        return response()->json([
            'user' => $user,
        ], 201);
    }

}
