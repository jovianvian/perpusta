@extends('layouts.guest')

@section('content')
<div class="text-center mb-8">
    <h2 class="text-3xl font-bold text-white">Create Account</h2>
    <p class="text-slate-400 mt-2">Join us and start your reading journey</p>
</div>

@if ($errors->any())
<div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm text-left">
    <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('register.post') }}" method="POST" class="space-y-5">
    @csrf

    <div>
        <label class="block text-sm font-medium text-slate-400 mb-2">Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
            placeholder="John Doe">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-400 mb-2">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
            placeholder="name@example.com">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-400 mb-2">Password</label>
        <input type="password" name="password" required autocomplete="new-password"
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
            placeholder="Create a strong password">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-400 mb-2">Confirm Password</label>
        <input type="password" name="password_confirmation" required autocomplete="new-password"
            class="w-full bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-white placeholder-slate-400 py-3 px-4 transition-all"
            placeholder="Repeat password">
    </div>

    <!-- ReCAPTCHA -->
    @if (!empty($recaptcha_site_key))
    <div id="googleCaptchaContainer" class="flex justify-center py-2">
        <div class="g-recaptcha" data-sitekey="{{ $recaptcha_site_key }}" data-theme="dark"></div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    <!-- Math Fallback -->
    <div id="mathCaptchaContainer" class="bg-slate-800 p-4 rounded-lg border border-slate-700"
        style="@if (!empty($recaptcha_site_key)) display: none; @endif">
        <div class="text-sm text-slate-300 mb-3 text-center">
            @if (!empty($recaptcha_site_key))
                If captcha fails, please solve this:
            @else
                Security check:
            @endif
        </div>
        <div class="flex items-center justify-center gap-3">
            <span class="text-lg font-bold text-white bg-slate-700 px-3 py-1 rounded">
                {{ $captcha_a ?? '?' }}
            </span>
            <span class="text-slate-400 font-bold">
                @php
                $symbol = '+';
                if (!empty($captcha_op)) {
                    $symbol = $captcha_op === 'x' ? '×' : $captcha_op;
                }
                @endphp
                {{ $symbol }}
            </span>
            <span class="text-lg font-bold text-white bg-slate-700 px-3 py-1 rounded">
                {{ $captcha_b ?? '?' }}
            </span>
            <span class="text-slate-400 font-bold">=</span>
            <input type="number" name="captcha_answer" id="captcha_answer"
                @if (empty($recaptcha_site_key)) required @endif
                class="w-20 bg-slate-700 border-transparent focus:ring-2 focus:ring-indigo-500 rounded-lg text-center text-white font-bold py-1">
        </div>
        @error('captcha')
        <p class="text-red-400 text-xs mt-2 text-center">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-lg shadow-indigo-600/30">
        Create Account
    </button>

    <div class="text-center text-sm text-slate-400">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Sign in</a>
    </div>
</form>

@if (!empty($recaptcha_site_key))
<script>
window.addEventListener('load', function () {
    var mathBox = document.getElementById('mathCaptchaContainer');
    var recaptchaBox = document.getElementById('googleCaptchaContainer');
    var mathInput = document.getElementById('captcha_answer');

    function showMathFallback() {
        if (mathBox) mathBox.style.display = 'block';
        if (mathInput) mathInput.required = true;
        if (recaptchaBox) recaptchaBox.style.display = 'none';
    }

    var hasGoogle = typeof grecaptcha !== 'undefined';

    if (!navigator.onLine || !hasGoogle) {
        showMathFallback();
    } else {
        if (mathInput) mathInput.required = false;
    }
});
</script>
@endif
@endsection