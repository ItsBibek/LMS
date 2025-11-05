<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->get('is_admin', false)) {
            return redirect()->route('admin.login.form');
        }
        return $next($request);
    }
}
