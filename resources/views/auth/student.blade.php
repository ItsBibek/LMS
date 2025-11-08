<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Login - Academia Library</title>
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
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Academia Library</h1>
        <p class="text-sm text-slate-600">Student Portal</p>
      </div>

      <!-- Login Card -->
      <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-6 py-5">
          <h2 class="text-xl font-semibold text-white flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Student Sign In
          </h2>
          <p class="text-blue-50 text-sm mt-1">Access your library account and reservations</p>
        </div>

        <div class="p-6">
          @if (session('status'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-start">
              <svg class="w-5 h-5 text-emerald-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span class="text-sm text-emerald-900">{{ session('status') }}</span>
            </div>
          @endif

          @if ($errors->any())
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-lg flex items-start">
              <svg class="w-5 h-5 text-rose-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span class="text-sm text-rose-900">{{ $errors->first() }}</span>
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}" class="space-y-5">
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
                       placeholder="student@example.com" />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                </div>
                <input type="password" name="password" required
                       class="w-full pl-10 pr-4 py-3 rounded-lg border-2 border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                       placeholder="••••••••" />
              </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all shadow-sm flex items-center justify-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
              </svg>
              Sign In to Student Portal
            </button>
          </form>

          <!-- Links -->
          <div class="mt-6 space-y-3">
            <div class="border-t border-slate-200 pt-4">
              <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center justify-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                Forgot your password?
              </a>
            </div>
            <div class="border-t border-slate-200 pt-4 text-center">
              <p class="text-sm text-slate-600 mb-2">Library Administrator?</p>
              <a href="{{ route('admin.login.form') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Switch to Admin Login
              </a>
            </div>
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
