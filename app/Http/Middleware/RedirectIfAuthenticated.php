<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        if ($request->expectsJson())
            return $next($request);

        foreach ($guards as $guard) {
            if ($guard !== null && Auth::guard($guard)->check()) {
                if ($guard == "admin")
                    return $next($request);

                // return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
