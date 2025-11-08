<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password - Academia Library</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-cyan-50 min-h-screen">
  <div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
      <!-- Logo/Brand -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center mb-4">
          <img src="{{ asset('storage/background/logo.png') }}" alt="Academia Library Logo" class="w-20 h-20 rounded-2xl shadow-lg" />
        </div>
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Forgot Password</h1>
        <p class="text-sm text-slate-600">Reset your password to access your account</p>
      </div>

      <!-- Reset Card -->
      <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-6 py-5">
          <h2 class="text-xl font-semibold text-white flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Password Reset
          </h2>
          <p class="text-blue-50 text-sm mt-1">Enter your email to receive a reset link</p>
        </div>

        <div class="p-6">
          @if (session('status'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-start">
              <svg class="w-5 h-5 text-emerald-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
                <p class="text-sm text-emerald-900 font-medium">Success!</p>
                <p class="text-sm text-emerald-800 mt-0.5">{{ session('status') }}</p>
              </div>
            </div>
          @endif

          @error('email')
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-lg flex items-start">
              <svg class="w-5 h-5 text-rose-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span class="text-sm text-rose-900">{{ $message }}</span>
            </div>
          @enderror

          <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full pl-10 pr-4 py-3 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                       placeholder="your-email@example.com" />
              </div>
              <p class="mt-2 text-xs text-slate-500">We'll send you a password reset link to this email</p>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all shadow-sm flex items-center justify-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
              </svg>
              Send Reset Link
            </button>
          </form>

          <!-- Links -->
          <div class="mt-6 border-t border-slate-200 pt-4 text-center">
            <a href="{{ route('student.login.form') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
              </svg>
              Back to Login
            </a>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <p class="text-center text-sm text-slate-600 mt-6">
        © {{ date('Y') }} Academia Library. All rights reserved.
      </p>
    </div>
  </div>
</body>
</html>
