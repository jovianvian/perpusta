<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Library System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-slate-900 text-slate-300 antialiased">
    <div class="min-h-screen flex">
        
        <!-- Left Side: Visual (60%) -->
        <div class="hidden lg:flex w-0 lg:w-[60%] relative bg-slate-800 items-center justify-center overflow-hidden">
            <!-- Background Image with Overlay -->
            <img src="https://images.unsplash.com/photo-1507842217121-9e8712db7db3?q=80&w=2070&auto=format&fit=crop" alt="Library Background" class="absolute inset-0 h-full w-full object-cover opacity-60 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-slate-900/40"></div>
            
            <!-- Content -->
            <div class="relative z-10 p-12 max-w-2xl text-white">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mb-8 shadow-2xl shadow-indigo-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold mb-6 leading-tight">Welcome to the <br/>Future of Learning.</h1>
                <p class="text-xl text-slate-300 font-light">"A library is not a luxury but one of the necessities of life."</p>
                <div class="mt-4 text-sm font-medium text-indigo-400">— Henry Ward Beecher</div>
            </div>
        </div>

        <!-- Right Side: Form (40%) -->
        <div class="w-full lg:w-[40%] flex items-center justify-center bg-slate-900 p-8 relative">
            <!-- Mobile Background Pattern -->
            <div class="absolute inset-0 bg-grid-slate-800/[0.2] bg-[bottom_1px_center] lg:hidden"></div>
            
            <div class="w-full max-w-md relative z-10">
                <!-- Mobile Logo -->
                <div class="flex justify-center mb-8 lg:hidden">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>

                @yield('content')
                
            </div>
        </div>
    </div>
</body>
</html>