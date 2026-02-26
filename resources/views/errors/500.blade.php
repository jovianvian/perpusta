<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server Error - Library System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-slate-900 text-slate-300 h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full text-center">
        <div class="mb-6 flex justify-center">
            <div class="w-20 h-20 bg-red-500/10 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-white mb-4">Terjadi Kesalahan Server</h1>
        <p class="text-slate-400 mb-8">Sistem mengalami masalah internal. Silakan coba lagi nanti atau hubungi administrator.</p>
        
        @if(app()->bound('debug') && app('debug'))
            <!-- Debug info would go here if enabled -->
        @endif

        <div class="flex justify-center gap-4">
            <a href="{{ url('/home') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-indigo-600/20">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>