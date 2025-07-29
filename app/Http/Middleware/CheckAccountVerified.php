<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ResponseTrait;
use App\Services\CheckAccountVerifiedService;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountVerified
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user=$request->user();
        $verificationResponse = CheckAccountVerifiedService::checkVerificationStatus($user);
        if ($verificationResponse) {
            return $verificationResponse; // لو الحساب غير مفعل، يرجع رسالة الخطأ
        }
        return $next($request);
    }
}
