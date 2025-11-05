<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Student')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
   html { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, 'Apple Color Emoji', 'Segoe UI Emoji'; }
  </style>
 </head>
 <body class="bg-slate-50 text-slate-800">
  <main class="min-h-screen px-4 sm:px-6 lg:px-8 py-8">
   <div class="max-w-6xl mx-auto">
    <header class="mb-6">
     <div class="flex items-center justify-between gap-3">
      <div>
       <h1 class="text-xl font-semibold">@yield('header', 'Profile')</h1>
       <p class="text-sm text-slate-500">@yield('subheader')</p>
      </div>
      @if(session()->has('student_batch'))
       <form method="POST" action="{{ route('student.logout') }}">
        @csrf
        <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">Logout</button>
       </form>
      @endif
     </div>
    </header>
    @yield('content')
   </div>
  </main>
 </body>
</html>
