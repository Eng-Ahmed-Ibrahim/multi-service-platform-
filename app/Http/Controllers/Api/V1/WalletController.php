<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    use ResponseTrait;
    public function balance(Request $request)
    {
        $balance = $request->user()->balance;
        $data = [
            "balance" => $balance,
        ];
        
        return $this->Response($data, "Balance", 200);
    }
    public function transactions(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $transactions = $user->walletTransactions()->latest()->get();
            $data=[
                'total' => $transactions->count(),
                'transactions' => $transactions,
            ];
            return $this->Response($data, "Transactions", 200);
        }

        return $this->Response([], "User not found", 404);
    }
    public function deposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "amount" => "required|numeric|gt:0",
        ]);

        if ($validator->fails()) {
            return $this->Response($validator->errors(), "Validation Error", 422);
        }

        $user = $request->user();
        if ($user) {
            DB::transaction(function () use ($user, $request) {
                deposit($user, $request->amount, "Deposit to wallet");
            });

            return $this->Response([], "Deposit successful", 200);
        }

        return $this->Response([], "User not found", 404);
    }
}
