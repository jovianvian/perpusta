@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('System Maintenance') }}</h1>
    <p class="text-slate-600 dark:text-slate-400">{{ __('Database management and system tools') }}</p>
</div>

@if(session('success'))
<div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-lg flex items-center gap-3">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg flex items-center gap-3">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <!-- Database Backup -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Backup Database') }}</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Download a full SQL dump of the database.') }}</p>
            </div>
        </div>
        <p class="text-slate-500 text-sm mb-6">
            {{ __('This will generate a downloadable SQL file containing all your current data (Books, Users, Loans, Settings).') }}
        </p>
        <a href="{{ route('system.backup') }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
            {{ __('Download Backup (.sql)') }}
        </a>
    </div>

    <!-- Restore Database -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-blue-500/20 p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Restore Database') }}</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Restore system from a SQL backup file.') }}</p>
            </div>
        </div>
        <p class="text-slate-500 text-sm mb-6">
            {!! __('Upload a <strong>.sql</strong> file to restore the database. <br><span class=\"text-yellow-500 font-bold\">Warning:</span> Existing data will be updated/replaced by the backup file.') !!}
        </p>
        <form action="{{ route('system.restore') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="return confirm('{{ __('Are you sure you want to restore this database backup? Current data might be overwritten.') }}');">
            @csrf
            <div>
                <input type="file" name="backup_file" accept=".sql" required class="block w-full text-sm text-slate-400
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-600 file:text-white
                hover:file:bg-blue-700">
            </div>
            <button type="submit" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                {{ __('Restore Database') }}
            </button>
        </form>
    </div>

    <!-- Reset Database -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-red-500/20 p-6 relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-500/10 rounded-full blur-2xl"></div>
        
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-lg bg-red-500/20 flex items-center justify-center text-red-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Reset Database') }}</h2>
                <p class="text-sm text-red-500 dark:text-red-400">{{ __('Warning: Destructive Action!') }}</p>
            </div>
        </div>
        <p class="text-slate-600 dark:text-slate-400 text-sm mb-6">
            {!! __('This will <strong class=\"text-red-500 dark:text-red-400\">WIPE ALL DATA</strong> and re-seed the database with default initial data. This action cannot be undone unless you have a backup.') !!}
        </p>
        <form action="{{ route('system.reset') }}" method="POST" onsubmit="return confirm('{{ __('CRITICAL WARNING: Are you absolutely sure you want to delete ALL DATA and reset the system?') }}');">
            @csrf
            <button type="submit" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                {{ __('Reset System Data') }}
            </button>
        </form>
    </div>

</div>
@endsection
