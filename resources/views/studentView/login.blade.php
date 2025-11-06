@extends('layouts.app')

@section('title', 'Student Login')
@section('header', 'Student Login')
@section('subheader', 'Access your profile with email and password')

@section('content')
 <div class="max-w-md mx-auto bg-white border border-slate-200 rounded-xl p-6">
  @if ($errors->any())
   <div class="mb-4 text-sm text-rose-600">{{ $errors->first() }}</div>
  @endif
  @if (session('status'))
   <div class="mb-4 text-sm text-emerald-600">{{ session('status') }}</div>
  @endif
  <form method="POST" action="{{ route('student.login') }}" class="space-y-4">
   @csrf
   <div>
    <label class="block text-sm font-medium text-slate-700">Email</label>
    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
   </div>
   <div>
    <label class="block text-sm font-medium text-slate-700">Password</label>
    <input type="password" name="password" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required />
   </div>
   <div>
    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-white text-sm font-medium hover:bg-indigo-700 w-full">Sign in</button>
   </div>
  </form>
  <div class="mt-4 text-center text-xs">
   <a href="{{ route('student.register.show') }}" class="text-indigo-600 underline">Create a new account</a>
   <span class="mx-2">·</span>
   <a href="{{ route('password.request') }}" class="text-indigo-600 underline">Forgot password?</a>
  </div>
 </div>
@endsection

