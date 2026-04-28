@extends('layouts.guest')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-2xl font-bold text-white">Verifikasi OTP Login</h2>
    <p class="text-slate-400 mt-2">Masukkan kode OTP 6 digit dari email Anda.</p>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
    {{ session('success') }}
</div>
@endif

@if(session('warning'))
<div class="mb-6 p-4 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 text-sm">
    {{ session('warning') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
    {{ session('error') }}
</div>
@endif

<form method="POST" action="{{ route('login.otp.verify') }}" class="space-y-6">
    @csrf
    <div>
        <label for="email" class="block text-sm font-medium text-slate-400 mb-2">Email Address</label>
        <input id="email" name="email" type="email" value="{{ old('email', $email ?? '') }}" required
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
            placeholder="name@example.com">
    </div>

    <div>
        <label for="otp" class="block text-sm font-medium text-slate-400 mb-2">OTP Code</label>
        <input id="otp" name="otp" type="text" value="{{ old('otp') }}" required maxlength="6"
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all text-center tracking-[0.5em] text-xl font-mono"
            placeholder="123456">
    </div>

    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-lg shadow-indigo-600/30">
        Login Sekarang
    </button>

    <div class="text-center text-sm text-slate-400 space-y-1">
        <div>
            Belum dapat OTP?
            <a href="{{ route('login.otp.form') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Kirim Ulang</a>
        </div>
        <div>
            Kembali ke
            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Login Password</a>
        </div>
    </div>
</form>
@endsection

