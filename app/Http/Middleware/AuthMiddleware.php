<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('id')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }

        return $next($request);
    }
}
