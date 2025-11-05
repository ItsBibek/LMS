<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('student_batch')) {
            return redirect()->route('student.login.form');
        }
        return $next($request);
    }
}
