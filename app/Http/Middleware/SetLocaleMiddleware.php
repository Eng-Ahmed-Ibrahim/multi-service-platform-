<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Lang', 'en');
        $supportedLocales = ['en', 'ar'];
        $locale = in_array($locale, $supportedLocales) ? $locale : 'en';        
        app()->setLocale($locale);

        
        return $next($request);
    }
}