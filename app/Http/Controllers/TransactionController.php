<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionCollection;
use App\Models\Account;
use Illuminate\Http\Request;

class TransactionController extends Controller
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
                'transaction' => $account->transactions()->get(),
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:deposit,withdrawal,balance'
        ]);
        $account = Account::where('user_id', auth()->id())->first();

        if (!$account) {
            return response()->json([
                'ok' => false,
                'error' => 'Account not found'
            ], 404);
        }

        if($request->type === 'withdrawal' && $account->balance < $request->amount) {
            return response()->json([
                'ok' => false,
                'error' => 'Insufficient funds'
            ], 400);
        }

        $transaction = $account->transactions()->create([
            'amount' => $request->amount,
            'type' => $request->type,
        ]);

        if($request->type === 'deposit') {
            $account->balance += $request->amount;
        }

        if($request->type === 'withdrawal') {
            $account->balance -= $request->amount;
        }

        return response()->json([
            'ok' => true,
            'data' => [
                'transaction' => $transaction,
            ]
        ], 201);
    }

    public function getByAccountNumber($numberAccount)
    {
        $account = Account::where('account_number', $numberAccount)->first();

        if (!$account) {
            return response()->json([
                'ok' => false,
                'error' => 'Account not found'
            ], 404);
        }

        return new TransactionCollection($account->transactions()->latest()->get());
    }
}
