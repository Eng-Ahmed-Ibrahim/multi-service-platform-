<?php

use App\Models\Notification;

use App\Models\BusinessSetting;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

if (! function_exists('deposit')) {
    function deposit($user, $amount, $description = null)
    {
        $user->increment('balance', $amount);

        WalletTransaction::create([
            'accountable_type' => get_class($user),
            'accountable_id' => $user->id,
            'amount' => $amount,
            'description' => $description,
            'type' => 'deposit',
        ]);

        return true;
    }
}
if (! function_exists('getBusinessSetting')) {
    function getBusinessSetting($key)
    {
        $setting = BusinessSetting::where('key', $key)->first();
        return $setting ? $setting->value : null; 
    }
}
if (! function_exists('reduceBalance')) {
    function reduceBalance($user, $amount, $description = null)
    {
        $user->decrement('balance', $amount);
        WalletTransaction::create([
            'accountable_type' => get_class($user),
            'accountable_id' => $user->id,
            'amount' => $amount,
            'description' => $description,
            'type' => 'deduction',
        ]);
        return true;
    }
}
if (! function_exists('adminProfit')) {
    function adminProfit($final_price)
    {
        $percentage = getBusinessSetting('profit_percentage') ?? 0;
        $admin_profit = ($final_price * $percentage) / 100;
        return $admin_profit;
    
    }
}