<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $adminEmail = env('ADMIN_EMAIL', 'anusha078@academiacollege.edu.np');
        $adminPassword = env('ADMIN_PASSWORD', 'demo123@');

        if ($email === $adminEmail && $password === $adminPassword) {
            $request->session()->put('is_admin', true);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->forget('is_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login.form');
    }

    public function sendResetCode(Request $request)
    {
        $adminEmail = env('ADMIN_EMAIL', 'anusha078@academiacollege.edu.np');
        // generate 6-digit code and cache for 10 minutes
        $code = (string) random_int(100000, 999999);
        Cache::put('admin_reset_code', $code, now()->addMinutes(10));
        try {
            Mail::raw('Your admin reset code is: ' . $code, function ($m) use ($adminEmail) {
                $m->from(config('mail.from.address'), config('mail.from.name'));
                $m->to($adminEmail)->subject('Admin Password Reset Code');
            });
            return back()->with('status', 'A reset code has been sent to ' . $adminEmail . '.');
        } catch (\Throwable $e) {
            Log::error('Admin reset email failed: '.$e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['email' => 'Could not send email. Please check mail configuration.']);
        }
    }
}
