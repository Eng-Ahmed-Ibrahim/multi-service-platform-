<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;

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
            return $this->Response($data, __("messages.Transactions"), 200);
        }

        return $this->Response([], __("messages.User not found"), 404);
    }
    public function deposit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "amount" => "required|numeric|gt:0",
        ]);

        if ($validator->fails()) {
            return $this->Response($validator->errors(), __("messages.Validation Error"), 422);
        }

        $user = $request->user();
        if ($user) {
            DB::transaction(function () use ($user, $request) {
                deposit($user, $request->amount, "Deposit to wallet");
            });

            return $this->Response([], __("messages.Deposit successfully"), 200);
        }

        return $this->Response([], __("messages.User not found"), 404);
    }
}
