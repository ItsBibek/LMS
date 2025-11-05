<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        if ($username === 'academialibrary' && $password === 'AIClms69') {
            $request->session()->put('is_admin', true);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['username' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->forget('is_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login.form');
    }
}
