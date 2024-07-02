<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Account;
use App\Models\User;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $page = $request->query('page', 1);

        return new UserCollection(User::where('role', 'user')->latest()->paginate($perPage, ["*"], 'page', $page));
    }

    public function show(Request $request, $id)
    {
        $user = \App\Models\User::find($id);

        if (!$user) {
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

        $user = User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => Str::random(60),
            'role' => 'user',
        ]);

        Account::create([
            'user_id' => $user->id,
            'balance' => 0,
            'account_number' => rand(0, 99999999),
        ]);

        return response()
            ->json([
                'user' => $user,
            ], 201)
            ->withHeaders([
                'Access-Control-Allow-Origin' => 'http://localhost:5173/',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, OPTIONS',
                'Access-Control-Allow-Headers' => 'Origin, Content-Type, X-Auth-Token , Cookie',
            ]);
    }
}
