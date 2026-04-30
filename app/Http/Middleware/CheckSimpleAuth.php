<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSimpleAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Cek session 'user_id' yang kita set di SimpleLoginController
        if (!session('user_id')) {
            return redirect('/login')->withErrors(['msg' => 'Silakan login terlebih dahulu.']);
        }
        
        return $next($request);
    }
}