<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $account = Account::where('user_id', auth()->id())->first();

        if (!$account) {
            return response()->json([
                'ok' => false,
                'error' => 'Account not found'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'data' => [
                'account' => $account,
            ]
        ], 200);
    }
}
