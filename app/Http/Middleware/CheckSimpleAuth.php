<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSimpleAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('user')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
