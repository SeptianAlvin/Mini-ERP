<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Money Track')</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 font-sans text-gray-900">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-indigo-500 text-white shrink-0 hidden md:flex flex-col">

            <div class="px-6 text-2xl font-bold italic mb-6">
                    <img src="{{ asset('storage/images/logo.png') }}" alt="Logo Aplikasi" class="w-20 h-19 mx-auto mb-2">

                Money Track
            </div>
            <nav class="flex-1 px-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="block py-2.5 px-4 rounded transition-colors hover:bg-indigo-600">Dashboard</a>
                <a href="{{ route('transaction') }}"
                    class="block py-2.5 px-4 rounded transition-colors hover:bg-indigo-600">Transaction</a>
                <a href="{{ route('categories') }}"
                    class="block py-2.5 px-4 rounded transition-colors hover:bg-indigo-600">Category</a>
                <a href="{{ route('dream') }}"
                    class="block py-2.5 px-4 rounded transition-colors hover:bg-indigo-600">Dream Planning</a>
            </nav>
        </aside>
        <main class="flex-1 overflow-y-auto">
            <header class="bg-white shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold">@yield('page-title', 'Dashboard')</h1>
                    <div class="flex items-center">
                        <span class="bg-indigo-500 text-white py-1 px-3 rounded-full">halo, Alvin</span>
                        <button
                            class="ml-4 bg-gray-200 text-gray-700 py-1 px-3 rounded hover:bg-gray-300">Logout</button>
                    </div>
                </div>
            </header>
            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
