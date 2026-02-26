@extends('layouts.guest')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-2xl font-bold text-white">Forgot Password</h2>
    <p class="text-slate-400 mt-2">Enter your email to receive a reset link</p>
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

<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
    @csrf

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-400 mb-2">Reset via</label>
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer border border-slate-600 rounded-lg p-3 flex items-center justify-center gap-2 hover:bg-slate-700 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-500/10 has-[:checked]:text-indigo-400">
                    <input type="radio" name="method" value="email" class="hidden" checked onchange="toggleMethod('email')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Email
                </label>
                <label class="cursor-pointer border border-slate-600 rounded-lg p-3 flex items-center justify-center gap-2 hover:bg-slate-700 transition-colors has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-500/10 has-[:checked]:text-emerald-400">
                    <input type="radio" name="method" value="whatsapp" class="hidden" onchange="toggleMethod('whatsapp')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    WhatsApp
                </label>
            </div>
        </div>

        <div id="emailInput">
            <label for="email" class="block text-sm font-medium text-slate-400 mb-2">Email Address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
                placeholder="name@example.com">
        </div>

        <div id="whatsappInput" class="hidden">
            <label for="whatsapp" class="block text-sm font-medium text-slate-400 mb-2">WhatsApp Number</label>
            <input id="whatsapp" name="whatsapp" type="text" value="{{ old('whatsapp') }}"
                class="w-full bg-slate-700 border-transparent focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
                placeholder="081234567890">
            <p class="text-xs text-slate-500 mt-1">*Nomor harus terdaftar di sistem</p>
        </div>
    </div>

    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-lg shadow-indigo-600/30">
        Send Reset Link / OTP
    </button>

    <div class="text-center text-sm text-slate-400">
        Remember your password? 
        <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Back to Login</a>
    </div>
</form>

<script>
    function toggleMethod(method) {
        if (method === 'email') {
            document.getElementById('emailInput').classList.remove('hidden');
            document.getElementById('whatsappInput').classList.add('hidden');
            document.getElementById('email').required = true;
            document.getElementById('whatsapp').required = false;
        } else {
            document.getElementById('emailInput').classList.add('hidden');
            document.getElementById('whatsappInput').classList.remove('hidden');
            document.getElementById('email').required = false;
            document.getElementById('whatsapp').required = true;
        }
    }
</script>
@endsection
