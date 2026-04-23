@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Application Settings') }}</h1>
    <p class="text-slate-600 dark:text-slate-400">{{ __('Configure general application preferences') }}</p>
</div>

@if(session('success'))
    <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        {{ session('success') }}
    </div>
@endif

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden max-w-4xl">
    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            {{ __('General Configuration') }}
        </h2>
    </div>

    <div class="p-6">
        <form action="{{ route('app_settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="site_name" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Website Name') }}</label>
                <div class="md:col-span-2">
                    <input type="text" name="site_name" id="site_name"
                        value="{{ old('site_name', $setting->site_name ?? '') }}" required
                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4 shadow-sm transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="manager_name" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Manager Name') }}</label>
                <div class="md:col-span-2">
                    <input type="text" name="manager_name" id="manager_name"
                        value="{{ old('manager_name', $setting->manager_name ?? '') }}"
                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4 shadow-sm transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Address') }}</label>
                <div class="md:col-span-2">
                    <textarea name="address" id="address" rows="3"
                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4 shadow-sm transition-colors">{{ old('address', $setting->address ?? '') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="contact_info" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Contact Info') }}</label>
                <div class="md:col-span-2">
                    <input type="text" name="contact_info" id="contact_info"
                        value="{{ old('contact_info', $setting->contact_info ?? '') }}"
                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4 shadow-sm transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="logo" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Website Logo') }}</label>
                <div class="md:col-span-2">
                    @if(isset($setting) && $setting->logo)
                        <div class="mb-4 p-2 bg-slate-100 dark:bg-slate-700/50 rounded-lg inline-block border border-slate-300 dark:border-slate-600">
                            <img src="{{ Storage::url($setting->logo) }}" alt="Current Logo" class="h-16 w-auto object-contain">
                        </div>
                    @endif

                    <div class="flex items-center justify-center w-full">
                        <label for="logo" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 dark:border-slate-600 border-dashed rounded-lg cursor-pointer bg-slate-100 dark:bg-slate-700/30 hover:bg-slate-200 dark:hover:bg-slate-700/50 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-slate-500 dark:text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-slate-700 dark:text-slate-400"><span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}</p>
                                <p class="text-xs text-slate-500">{{ __('SVG, PNG, JPG or GIF (MAX. 2MB)') }}</p>
                            </div>
                            <input id="logo" name="logo" type="file" class="hidden" accept="image/*" />
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">{{ __('Leave empty if you do not want to change the current logo.') }}</p>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg transition-colors shadow-lg shadow-indigo-600/20 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Save Settings') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
