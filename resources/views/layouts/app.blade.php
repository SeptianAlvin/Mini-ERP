<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Money Track')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite('resources/css/app.css')
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-slate-100 font-sans text-slate-800 antialiased selection:bg-indigo-500 selection:text-white">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 shrink-0 hidden md:flex flex-col relative z-20">
            <!-- Logo Section -->
            <div class="px-6 pt-8 pb-6 border-b border-slate-700/50">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Aplikasi" class="w-10 h-10 rounded shadow-sm bg-white p-0.5">
                    <span class="text-xl font-bold tracking-tight text-white">Money<span class="text-indigo-400">Track</span></span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto">
                <p class="px-3 text-xs font-medium text-slate-400 uppercase tracking-wider mb-3">MENU</p>
                
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-500 text-white font-medium' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('transaction') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('transaction*') ? 'bg-indigo-500 text-white font-medium' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('transaction*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Transactions</span>
                </a>

                <a href="{{ route('categories') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('categories*') ? 'bg-indigo-500 text-white font-medium' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('categories*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span>Categories</span>
                </a>

                <a href="{{ route('dream') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('dream*') ? 'bg-indigo-500 text-white font-medium' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dream*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span>Dream Planner</span>
                </a>
            </nav>

            <!-- Bottom Profile / Settings -->
            <div class="p-4 border-t border-slate-700/50">
                <div class="flex items-center px-3 py-2.5 rounded-lg hover:bg-slate-700 cursor-pointer transition-colors border border-transparent hover:border-slate-600">
                    <div class="w-8 h-8 rounded-full bg-slate-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="ml-3 truncate">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-100">
            <!-- Header -->
            <header class="bg-white border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                <div class="flex justify-between items-center px-8 py-4">
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">@yield('page-title', 'Dashboard')</h1>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Notification Bell -->
                        <button class="text-slate-400 hover:text-indigo-600 transition-colors relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="absolute top-0 right-0.5 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
                        </button>
                        
                        <!-- Profile Action -->
                        <div class="flex items-center border-l border-slate-200 pl-6">
                            <span class="text-sm text-slate-500 mr-4 hidden sm:block">Welcome back, <span class="font-medium text-slate-800">{{ explode(' ', Auth::user()->name)[0] }}</span></span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-slate-500 hover:text-red-600 text-sm py-2 px-3 rounded-lg transition-colors duration-200 flex items-center hover:bg-red-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>

</html>
