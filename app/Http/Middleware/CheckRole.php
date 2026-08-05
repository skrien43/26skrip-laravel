<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!in_array(session('role'), $roles)) {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }
        return $next($request);
    }
}
