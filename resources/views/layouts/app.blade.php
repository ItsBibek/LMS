<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Library Management System')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
   html { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, 'Apple Color Emoji', 'Segoe UI Emoji'; }
  </style>
 </head>
 <body class="bg-slate-50 text-slate-800">
  <div class="min-h-screen flex">
   <aside class="w-64 bg-white border-r border-slate-200 hidden md:block">
    <div class="p-4 border-b border-slate-200 flex items-center gap-3">
     <!-- <img src="{{ asset('images/academia-logo.png') }}" alt="Academia International College" class="w-10 h-10 rounded-lg object-cover" /> -->
     <div>
      <h1 class="text-base font-semibold"> Academia Library</h1>
     </div>
    </div>
    <nav class="p-3 space-y-1">
     <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md hover:bg-slate-50 {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-indigo-700' : 'text-slate-700' }}">Dashboard</a>
     <a href="{{ route('books.index') }}" class="block px-3 py-2 rounded-md hover:bg-slate-50 {{ request()->routeIs('books.index') ? 'bg-slate-100 text-indigo-700' : 'text-slate-700' }}">Book Search</a>
     <a href="{{ route('students.index') }}" class="block px-3 py-2 rounded-md hover:bg-slate-50 {{ request()->routeIs('students.index') ? 'bg-slate-100 text-indigo-700' : 'text-slate-700' }}">Student Search</a>
     <a href="{{ route('students.manage') }}" class="block px-3 py-2 rounded-md hover:bg-slate-50 {{ request()->routeIs('students.manage') || request()->routeIs('students.create') || request()->routeIs('students.edit') ? 'bg-slate-100 text-indigo-700' : 'text-slate-700' }}">Manage Students</a>

     <a href="{{ route('reservations.index') }}" class="block px-3 py-2 rounded-md hover:bg-slate-50 {{ request()->routeIs('reservations.index') ? 'bg-slate-100 text-indigo-700' : 'text-slate-700' }}">Reservations</a>

    </nav>
   </aside>

   <div class="flex-1 flex flex-col min-w-0">
    <header class="bg-white border-b border-slate-200">
     <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3 md:hidden">
       <!--<img src="{{ asset('images/academia-logo.png') }}" alt="Academia International College" class="w-10 h-10 rounded-lg object-cover" />-->
       <div>
        <h1 class="text-base font-semibold">@yield('header', 'Library Management System')</h1>
       </div>
      </div>
      <div class="hidden md:block">
       <h1 class="text-xl font-semibold">@yield('header', 'Library Management System')</h1>
       <p class="text-sm text-slate-500">@yield('subheader')</p>
      </div>
      <div class="flex items-center gap-2">
       @yield('header_actions')
       @if(session('is_admin'))
        <form method="POST" action="{{ route('admin.logout') }}">
         @csrf
         <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-3 py-2 text-sm font-medium hover:bg-slate-50">Logout</button>
        </form>
       @endif
      </div>
     </div>
    </header>

    <main class="px-4 sm:px-6 lg:px-8 py-8">
     @yield('content')
    </main>

    <footer class="mt-auto py-6 text-center text-sm text-slate-500">
     <span>&copy; {{ date('Y') }} Library Management System</span>
    </footer>
   </div>
  </div>
 </body>
</html>
