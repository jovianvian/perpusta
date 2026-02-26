@extends('layouts.guest')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-2xl font-bold text-white">Verify OTP</h2>
    <p class="text-slate-400 mt-2">Enter the code sent to your WhatsApp</p>
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

<form method="POST" action="{{ route('password.verify_otp') }}" class="space-y-6">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}">

    <div>
        <label for="otp" class="block text-sm font-medium text-slate-400 mb-2">OTP Code</label>
        <input id="otp" name="otp" type="text" required autofocus
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all text-center tracking-[0.5em] text-xl font-mono"
            placeholder="123456" maxlength="6">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-slate-400 mb-2">New Password</label>
        <input id="password" name="password" type="password" required
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
            placeholder="Min. 6 characters">
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-slate-400 mb-2">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
            placeholder="Re-enter password">
    </div>

    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-lg shadow-indigo-600/30">
        Verify & Reset Password
    </button>
</form>
@endsection
