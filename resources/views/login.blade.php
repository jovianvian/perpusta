@extends('layouts.guest')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-3xl font-bold text-white">Welcome Back</h2>
    <p class="text-slate-400 mt-2">Sign in to access your library account</p>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
    {{ session('error') }}
</div>
@endif

@if (session('unverified_email'))
<div class="mb-6 p-4 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 text-sm">
    <div class="flex items-center gap-2 mb-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <strong>Email not verified</strong>
    </div>
    <form action="{{ route('resend.verification') }}" method="POST">
        @csrf
        <input type="hidden" name="email" value="{{ session('unverified_email') }}">
        <button type="submit" class="text-amber-300 hover:text-amber-200 underline font-medium">
            Resend verification email
        </button>
    </form>
</div>
@endif

<form method="POST" action="{{ url('/login') }}" class="space-y-6">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-slate-400 mb-2">Email Address</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" 
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
            placeholder="name@example.com">
    </div>

    <div>
        <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-sm font-medium text-slate-400">Password</label>
            <a href="{{ route('password.request') }}" class="text-sm text-indigo-400 hover:text-indigo-300">Forgot password?</a>
        </div>
        <input id="password" name="password" type="password" required autocomplete="current-password"
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
            placeholder="Enter your password">
    </div>

    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-lg shadow-indigo-600/30">
        Sign In
    </button>

    <div class="text-center text-sm text-slate-400">
        Don't have an account? 
        <a href="{{ url('/register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Create account</a>
    </div>
</form>
@endsection