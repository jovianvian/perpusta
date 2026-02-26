@extends('layouts.guest')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-2xl font-bold text-white">Reset Password</h2>
    <p class="text-slate-400 mt-2">Create a new password for your account</p>
</div>

@if(session('error'))
<div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
    {{ session('error') }}
</div>
@endif

<form method="POST" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label for="email" class="block text-sm font-medium text-slate-400 mb-2">Email Address</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
            placeholder="name@example.com">
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
        Reset Password
    </button>
</form>
@endsection
