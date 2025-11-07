<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Check if this is an admin route (starts with /admin, /dashboard, /books, /students, /reservations)
            if ($request->is('admin/*') || $request->is('dashboard') || $request->is('books*') || $request->is('students*') || $request->is('reservations*')) {
                return route('admin.login.form');
            }
            // Student routes - redirect to student login (root)
            return route('login');
        }
    }
}
