<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StudentAuthController extends Controller
{
    public function showLogin()
    {
        return view('studentView.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
            'batch_no' => ['required','string'],
        ]);

        $student = User::where('email', $request->email)
            ->where('batch_no', $request->batch_no)
            ->first();

        if (!$student) {
            return back()->withErrors(['email' => 'No matching student found.'])->withInput();
        }

        $request->session()->put('student_batch', $student->batch_no);
        return redirect()->route('student.profile');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('student_batch');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student.login.form');
    }
}
