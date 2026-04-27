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

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden w-full max-w-5xl">
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

            <details class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/30 p-4" open>
                <summary class="cursor-pointer list-none flex items-center justify-between text-sm font-semibold text-slate-700 dark:text-slate-200">
                    <span>{{ __('Branding & Theme (Advanced)') }}</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('Click to expand/collapse') }}</span>
                </summary>
                <div class="mt-4 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="logo" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Website Logo') }}</label>
                <div class="md:col-span-2">
                    @if(isset($setting) && $setting->logo)
                        <div class="mb-4 p-2 bg-slate-100 dark:bg-slate-700/50 rounded-lg inline-block border border-slate-300 dark:border-slate-600">
                            <img src="{{ Storage::url($setting->logo) }}" alt="Current Logo" class="h-16 w-auto object-contain">
                        </div>
                    @endif

                    <div id="logo_dropzone" class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 dark:border-slate-600 border-dashed rounded-lg cursor-pointer bg-slate-100 dark:bg-slate-700/30 hover:bg-slate-200 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 pointer-events-none">
                            <svg class="w-8 h-8 mb-4 text-slate-500 dark:text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-slate-700 dark:text-slate-400"><span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}</p>
                            <p class="text-xs text-slate-500">{{ __('SVG, PNG, JPG or GIF (MAX. 2MB)') }}</p>
                            <p id="logo_file_name" class="text-xs text-indigo-600 dark:text-indigo-400 mt-2 hidden"></p>
                        </div>
                        <input id="logo" name="logo" type="file" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" />
                    </div>
                    <p class="mt-2 text-xs text-slate-500">{{ __('Leave empty if you do not want to change the current logo.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="background_image" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Background Image') }}</label>
                <div class="md:col-span-2">
                    @if(isset($setting) && $setting->background_image)
                        <div class="mb-4 rounded-lg overflow-hidden border border-slate-300 dark:border-slate-600">
                            <img src="{{ Storage::url($setting->background_image) }}" alt="Current Background" class="h-36 w-full object-cover">
                        </div>
                    @endif

                    <div id="background_dropzone" class="relative flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg cursor-pointer bg-slate-100 dark:bg-slate-700/30 hover:bg-slate-200 dark:hover:bg-slate-700/50 transition-colors">
                        <svg class="w-8 h-8 mb-3 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm text-slate-700 dark:text-slate-300"><span class="font-semibold">{{ __('Drag & drop background image') }}</span></p>
                        <p class="text-xs text-slate-500 mt-1">{{ __('or click this box to choose file') }}</p>
                        <p id="background_file_name" class="text-xs text-indigo-600 dark:text-indigo-400 mt-2 hidden"></p>
                        <input id="background_image" name="background_image" type="file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" />
                    </div>
                    <p class="mt-2 text-xs text-slate-500">{{ __('Upload school photo for app background (JPG/PNG/WEBP, max 4MB). Leave empty to keep current.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="theme_primary_color" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Theme Primary Color') }}</label>
                <div class="md:col-span-2 flex items-center gap-3" data-color-sync="primary">
                    <input type="color" name="theme_primary_color" id="theme_primary_color"
                        value="{{ old('theme_primary_color', $setting->theme_primary_color ?? '#4f46e5') }}"
                        class="js-theme-color-picker h-11 w-14 rounded border border-slate-300 dark:border-transparent bg-white dark:bg-slate-700 cursor-pointer">
                    <input type="text" id="theme_primary_color_hex"
                        value="{{ old('theme_primary_color', $setting->theme_primary_color ?? '#4f46e5') }}"
                        class="js-theme-color-hex w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent rounded-lg text-slate-900 dark:text-white py-2.5 px-4 shadow-sm transition-colors"
                        placeholder="#4f46e5" spellcheck="false" autocomplete="off">
                    <span class="js-theme-color-preview inline-flex h-11 w-11 rounded-lg border border-slate-300 dark:border-slate-600" style="background-color: {{ old('theme_primary_color', $setting->theme_primary_color ?? '#4f46e5') }};"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="theme_secondary_color" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Theme Secondary Color') }}</label>
                <div class="md:col-span-2 flex items-center gap-3" data-color-sync="secondary">
                    <input type="color" name="theme_secondary_color" id="theme_secondary_color"
                        value="{{ old('theme_secondary_color', $setting->theme_secondary_color ?? '#3730a3') }}"
                        class="js-theme-color-picker h-11 w-14 rounded border border-slate-300 dark:border-transparent bg-white dark:bg-slate-700 cursor-pointer">
                    <input type="text" id="theme_secondary_color_hex"
                        value="{{ old('theme_secondary_color', $setting->theme_secondary_color ?? '#3730a3') }}"
                        class="js-theme-color-hex w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent rounded-lg text-slate-900 dark:text-white py-2.5 px-4 shadow-sm transition-colors"
                        placeholder="#3730a3" spellcheck="false" autocomplete="off">
                    <span class="js-theme-color-preview inline-flex h-11 w-11 rounded-lg border border-slate-300 dark:border-slate-600" style="background-color: {{ old('theme_secondary_color', $setting->theme_secondary_color ?? '#3730a3') }};"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="app_background_color" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('App Background Color') }}</label>
                <div class="md:col-span-2 flex items-center gap-3" data-color-sync="app_bg">
                    <input type="color" name="app_background_color" id="app_background_color"
                        value="{{ old('app_background_color', $setting->app_background_color ?? '#f8fafc') }}"
                        class="js-theme-color-picker h-11 w-14 rounded border border-slate-300 dark:border-transparent bg-white dark:bg-slate-700 cursor-pointer">
                    <input type="text" id="app_background_color_hex"
                        value="{{ old('app_background_color', $setting->app_background_color ?? '#f8fafc') }}"
                        class="js-theme-color-hex w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent rounded-lg text-slate-900 dark:text-white py-2.5 px-4 shadow-sm transition-colors"
                        placeholder="#f8fafc" spellcheck="false" autocomplete="off">
                    <span class="js-theme-color-preview inline-flex h-11 w-11 rounded-lg border border-slate-300 dark:border-slate-600" style="background-color: {{ old('app_background_color', $setting->app_background_color ?? '#f8fafc') }};"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="sidebar_bg_color" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Sidebar Color') }}</label>
                <div class="md:col-span-2 flex items-center gap-3" data-color-sync="sidebar_bg">
                    <input type="color" name="sidebar_bg_color" id="sidebar_bg_color"
                        value="{{ old('sidebar_bg_color', $setting->sidebar_bg_color ?? '#0f172a') }}"
                        class="js-theme-color-picker h-11 w-14 rounded border border-slate-300 dark:border-transparent bg-white dark:bg-slate-700 cursor-pointer">
                    <input type="text" id="sidebar_bg_color_hex"
                        value="{{ old('sidebar_bg_color', $setting->sidebar_bg_color ?? '#0f172a') }}"
                        class="js-theme-color-hex w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent rounded-lg text-slate-900 dark:text-white py-2.5 px-4 shadow-sm transition-colors"
                        placeholder="#0f172a" spellcheck="false" autocomplete="off">
                    <span class="js-theme-color-preview inline-flex h-11 w-11 rounded-lg border border-slate-300 dark:border-slate-600" style="background-color: {{ old('sidebar_bg_color', $setting->sidebar_bg_color ?? '#0f172a') }};"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="topbar_bg_color" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Topbar Color') }}</label>
                <div class="md:col-span-2 flex items-center gap-3" data-color-sync="topbar_bg">
                    <input type="color" name="topbar_bg_color" id="topbar_bg_color"
                        value="{{ old('topbar_bg_color', $setting->topbar_bg_color ?? '#ffffff') }}"
                        class="js-theme-color-picker h-11 w-14 rounded border border-slate-300 dark:border-transparent bg-white dark:bg-slate-700 cursor-pointer">
                    <input type="text" id="topbar_bg_color_hex"
                        value="{{ old('topbar_bg_color', $setting->topbar_bg_color ?? '#ffffff') }}"
                        class="js-theme-color-hex w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent rounded-lg text-slate-900 dark:text-white py-2.5 px-4 shadow-sm transition-colors"
                        placeholder="#ffffff" spellcheck="false" autocomplete="off">
                    <span class="js-theme-color-preview inline-flex h-11 w-11 rounded-lg border border-slate-300 dark:border-slate-600" style="background-color: {{ old('topbar_bg_color', $setting->topbar_bg_color ?? '#ffffff') }};"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="background_overlay_opacity" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Background Overlay Opacity') }}</label>
                <div class="md:col-span-2">
                    <input type="range" name="background_overlay_opacity" id="background_overlay_opacity" min="0" max="1" step="0.01"
                        value="{{ old('background_overlay_opacity', isset($setting) ? ($setting->background_overlay_opacity ?? 0.88) : 0.88) }}"
                        class="w-full accent-indigo-600">
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        {{ __('Current opacity') }}:
                        <span id="background_overlay_opacity_value">{{ old('background_overlay_opacity', isset($setting) ? ($setting->background_overlay_opacity ?? 0.88) : 0.88) }}</span>
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <label for="footer_text" class="block text-sm font-medium text-slate-700 dark:text-slate-400 pt-2">{{ __('Footer Text') }}</label>
                <div class="md:col-span-2">
                    <textarea name="footer_text" id="footer_text" rows="2"
                        class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4 shadow-sm transition-colors"
                        placeholder="Contoh: © 2026 Sekolah Permata Harapan. All rights reserved.">{{ old('footer_text', $setting->footer_text ?? '') }}</textarea>
                </div>
            </div>
                </div>
            </details>

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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bindDropzone = (dropzone, fileInput, fileLabel) => {
        if (!dropzone || !fileInput) return;

        const setFileLabel = (file) => {
            if (!fileLabel) return;
            if (file) {
                fileLabel.textContent = file.name;
                fileLabel.classList.remove('hidden');
            } else {
                fileLabel.textContent = '';
                fileLabel.classList.add('hidden');
            }
        };

        fileInput.addEventListener('change', () => {
            setFileLabel(fileInput.files?.[0] ?? null);
        });

        ['dragenter', 'dragover'].forEach((evt) => {
            dropzone.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('ring-2', 'ring-indigo-500');
            });
        });

        ['dragleave', 'drop'].forEach((evt) => {
            dropzone.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('ring-2', 'ring-indigo-500');
            });
        });

        dropzone.addEventListener('drop', (e) => {
            const files = e.dataTransfer?.files;
            if (!files || !files.length) return;
            fileInput.files = files;
            setFileLabel(files[0]);
        });
    };

    const logoDropzone = document.getElementById('logo_dropzone');
    const logoUpload = document.getElementById('logo');
    const logoFileName = document.getElementById('logo_file_name');
    bindDropzone(logoDropzone, logoUpload, logoFileName);

    const backgroundDropzone = document.getElementById('background_dropzone');
    const backgroundUpload = document.getElementById('background_image');
    const backgroundFileName = document.getElementById('background_file_name');

    bindDropzone(backgroundDropzone, backgroundUpload, backgroundFileName);

    const overlayInput = document.getElementById('background_overlay_opacity');
    const overlayValue = document.getElementById('background_overlay_opacity_value');
    if (overlayInput && overlayValue) {
        const syncOverlayValue = () => {
            overlayValue.textContent = Number(overlayInput.value).toFixed(2);
        };
        overlayInput.addEventListener('input', syncOverlayValue);
        syncOverlayValue();
    }
    const groups = document.querySelectorAll('[data-color-sync]');
    if (!groups.length) return;

    const isValidHex = (val) => /^#([A-Fa-f0-9]{6})$/.test(val);
    const normalizeHex = (val) => {
        const x = String(val || '').trim();
        if (x === '') return '';
        return x.startsWith('#') ? x.toLowerCase() : `#${x.toLowerCase()}`;
    };

    groups.forEach((group) => {
        const picker = group.querySelector('.js-theme-color-picker');
        const hexInput = group.querySelector('.js-theme-color-hex');
        const preview = group.querySelector('.js-theme-color-preview');
        if (!picker || !hexInput || !preview) return;

        const applyValue = (val, updateHex = true, updatePicker = true) => {
            const normalized = normalizeHex(val);
            if (!isValidHex(normalized)) return false;

            if (updateHex) hexInput.value = normalized;
            if (updatePicker) picker.value = normalized;
            preview.style.backgroundColor = normalized;
            return true;
        };

        applyValue(picker.value);

        picker.addEventListener('input', () => {
            applyValue(picker.value, true, false);
        });

        hexInput.addEventListener('input', () => {
            applyValue(hexInput.value, true, true);
        });

        hexInput.addEventListener('blur', () => {
            if (!applyValue(hexInput.value, true, true)) {
                applyValue(picker.value, true, false);
            }
        });
    });
});
</script>
@endsection




