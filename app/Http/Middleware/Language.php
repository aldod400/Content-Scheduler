<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('lang') ?? session('locale', config('app.locale'));
        if (in_array($locale, ['en', 'ar'])) {
            app()->setLocale($locale);
            session()->put('locale', $locale);
        }
        return $next($request);
    }
}
