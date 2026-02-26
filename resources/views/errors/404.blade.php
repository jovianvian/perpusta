<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found - Library System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-slate-900 text-slate-300 h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-indigo-600 opacity-20">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-3xl font-bold text-white">Halaman Tidak Ditemukan</span>
            </div>
        </div>
        <p class="text-lg text-slate-400 mb-8">Maaf, halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/home') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-indigo-600/20">
                Kembali ke Dashboard
            </a>
            <button onclick="history.back()" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-lg font-medium transition-colors border border-slate-700">
                Kembali Sebelumnya
            </button>
        </div>
        @if(isset($exception) && $exception->getMessage())
        <div class="mt-8 p-4 bg-red-500/10 border border-red-500/20 rounded-lg text-left">
            <p class="text-xs text-red-400 font-mono break-all">Error: {{ $exception->getMessage() }}</p>
        </div>
        @endif
    </div>
</body>
</html>