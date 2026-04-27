<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Library System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @php
        $themePrimary = $globalSettings->theme_primary_color ?? '#4f46e5';
        $themeSecondary = $globalSettings->theme_secondary_color ?? '#3730a3';
        $themeBgImage = !empty($globalSettings?->background_image) ? Storage::url($globalSettings->background_image) : null;
        $themeAppBackground = $globalSettings->app_background_color ?? '#f8fafc';
        $themeSidebarBg = $globalSettings->sidebar_bg_color ?? '#0f172a';
        $themeTopbarBg = $globalSettings->topbar_bg_color ?? '#ffffff';
        $themeOverlayOpacity = isset($globalSettings->background_overlay_opacity)
            ? max(0, min(1, (float) $globalSettings->background_overlay_opacity))
            : 0.88;
    @endphp
    <style>
        :root {
            --app-primary: {{ $themePrimary }};
            --app-secondary: {{ $themeSecondary }};
            --app-bg: {{ $themeAppBackground }};
            --app-sidebar-bg: {{ $themeSidebarBg }};
            --app-topbar-bg: {{ $themeTopbarBg }};
        }

        .bg-indigo-600 { background-color: var(--app-primary) !important; }
        .hover\:bg-indigo-700:hover { background-color: var(--app-secondary) !important; }
        .text-indigo-400, .text-indigo-500, .text-indigo-600 { color: var(--app-primary) !important; }
        .border-indigo-500 { border-color: var(--app-primary) !important; }
        .focus\:border-indigo-500:focus { border-color: var(--app-primary) !important; }
        .focus\:ring-indigo-500:focus { --tw-ring-color: var(--app-primary) !important; }

        .app-sidebar {
            background-color: var(--app-sidebar-bg) !important;
        }
        .app-sidebar nav a:not(.bg-indigo-600) {
            color: rgba(241, 245, 249, 0.88) !important;
        }
        .app-sidebar nav a:not(.bg-indigo-600):hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
        .app-sidebar nav a.bg-indigo-600 {
            background-color: rgba(255, 255, 255, 0.2) !important;
            color: #ffffff !important;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.12);
        }
        .app-sidebar nav .js-nav-group-toggle,
        .app-sidebar nav > div.pt-4.pb-1 {
            color: rgba(226, 232, 240, 0.72) !important;
        }
        .app-sidebar .sidebar-border {
            border-color: rgba(148, 163, 184, 0.22) !important;
        }
        .app-topbar-custom {
            background-color: var(--app-topbar-bg) !important;
        }
    </style>
    <script>
        // --- THEME TOGGLE SCRIPT ---
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }

        // --- LOCATION TRACKING SCRIPT ---
        document.addEventListener("DOMContentLoaded", function() {
            // Function to handle location access
            function requestLocation() {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            // Success: Update location
                            updateLocation(position.coords.latitude, position.coords.longitude);
                            
                            // Save state
                            sessionStorage.setItem('location_granted', 'true');
                            
                            // Hide warning if exists
                            const warning = document.getElementById('locationWarning');
                            if(warning) warning.remove();
                        },
                        function(error) {
                            console.warn("Location access denied or error: " + error.message);
                            
                            // Try IP Geolocation Fallback before showing error
                            getIpLocation(error);
                        },
                        {
                            enableHighAccuracy: false, 
                            timeout: 10000, // Reduced to 10s since we have fallback
                            maximumAge: Infinity // Accept cached
                        }
                    );
                } else {
                    getIpLocation({ code: 0, message: "Geolocation not supported" });
                }
            }

            function getIpLocation(originalError) {
                // Fetch from free IP Geolocation API
                fetch('https://ipapi.co/json/')
                    .then(response => response.json())
                    .then(data => {
                        if(data.latitude && data.longitude) {
                            console.log("Used IP Geolocation Fallback");
                            updateLocation(data.latitude, data.longitude);
                            // Hide warning if exists
                            const warning = document.getElementById('locationWarning');
                            if(warning) warning.remove();
                        } else {
                            throw new Error("Invalid IP data");
                        }
                    })
                    .catch(err => {
                        console.error("IP Fallback failed:", err);
                        
                        // Show original error
                        let errorMsg = "Terjadi kesalahan saat mengambil lokasi.";
                        if (originalError.code === 1) { // PERMISSION_DENIED
                            errorMsg = "Akses lokasi ditolak. Mohon izinkan akses lokasi di pengaturan browser.";
                        } else if (originalError.code === 2) { // POSITION_UNAVAILABLE
                            errorMsg = "Informasi lokasi GPS tidak tersedia.";
                        } else if (originalError.code === 3) { // TIMEOUT
                            errorMsg = "Waktu permintaan lokasi GPS habis.";
                        }
                        
                        showLocationWarning(errorMsg);
                    });
            }

            function showLocationWarning(msg) {
                // Check if warning already shown
                if(document.getElementById('locationWarning')) return;

                const warningHtml = `
                    <div id="locationWarning" class="fixed inset-0 z-[100] bg-slate-900/95 flex items-center justify-center p-4 backdrop-blur-sm">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-8 text-center border-2 border-red-500 animate-pulse">
                            <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Akses Lokasi Diperlukan!</h2>
                            <p class="text-slate-600 dark:text-slate-300 mb-4">
                                ${msg || 'Mohon izinkan akses lokasi (GPS) browser Anda untuk melanjutkan menggunakan aplikasi ini.'}
                            </p>
                            <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg text-xs text-red-600 dark:text-red-400 mb-6 text-left">
                                <strong>Tips:</strong><br>
                                1. Klik ikon gembok/lokasi di address bar browser.<br>
                                2. Ubah "Location" menjadi "Allow" / "Izinkan".<br>
                                3. Klik tombol Coba Lagi di bawah.
                            </div>
                            <button onclick="window.location.reload()" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-red-600/30">
                                Coba Lagi / Reload Page
                            </button>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', warningHtml);
            }

            // Request immediately on load
            requestLocation();
        });

        function updateLocation(lat, lng) {
            // Gunakan fetch API untuk kirim data ke route update lokasi
            fetch("{{ route('update.location') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Location updated:", data);
            })
            .catch(error => console.error("Error updating location:", error));
        }

        // --- VOICE SEARCH SCRIPT ---
        document.addEventListener("DOMContentLoaded", function() {
            const voiceBtn = document.getElementById('voiceSearchBtn');
            const searchInput = document.getElementById('globalSearchInput');
            
            if (voiceBtn && searchInput) {
                if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    const recognition = new SpeechRecognition();
                    
                    recognition.continuous = false;
                    recognition.interimResults = false;
                    recognition.lang = '{{ app()->getLocale() == "id" ? "id-ID" : "en-US" }}'; // Sesuaikan bahasa

                    voiceBtn.addEventListener('click', function() {
                        if (voiceBtn.classList.contains('text-red-500')) {
                            recognition.stop();
                        } else {
                            recognition.start();
                        }
                    });

                    recognition.onstart = function() {
                        voiceBtn.classList.remove('text-slate-400');
                        voiceBtn.classList.add('text-red-500', 'animate-pulse');
                        searchInput.placeholder = '{{ __('Listening...') }}';
                    };

                    recognition.onend = function() {
                        voiceBtn.classList.remove('text-red-500', 'animate-pulse');
                        voiceBtn.classList.add('text-slate-400');
                        searchInput.placeholder = '{{ __('Search global...') }}';
                    };

                    recognition.onresult = function(event) {
                        const transcript = event.results[0][0].transcript;
                        searchInput.value = transcript;
                        
                        // Auto submit search form
                        searchInput.closest('form').submit();
                    };

                    recognition.onerror = function(event) {
                        console.error('Speech recognition error', event.error);
                        voiceBtn.classList.remove('text-red-500', 'animate-pulse');
                        voiceBtn.classList.add('text-slate-400');
                        searchInput.placeholder = '{{ __('Error. Try again.') }}';
                    };
                } else {
                    voiceBtn.style.display = 'none';
                    console.warn('Speech Recognition API not supported in this browser.');
                }
            }
        });

        // --- NOTIFICATION BADGE POLLING ---
        document.addEventListener("DOMContentLoaded", function() {
            const headerActions = document.querySelector('header .flex.items-center.gap-4.ml-4');
            if (!headerActions) return;

            function syncNotificationBadge(count) {
                const bellButton = document.querySelector('#notificationDropdown > button');
                if (!bellButton) return;

                let badge = document.getElementById('notificationBadge');
                if (count > 0) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.id = 'notificationBadge';
                        badge.className = 'absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 text-[10px] leading-[18px] text-center font-semibold text-white bg-red-500 rounded-full';
                        bellButton.appendChild(badge);
                    }
                    badge.textContent = count > 9 ? '9+' : String(count);
                } else if (badge) {
                    badge.remove();
                }
            }

            function refreshUnreadCount() {
                fetch("{{ route('notifications.unread_count') }}", {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then((res) => res.ok ? res.json() : null)
                .then((data) => {
                    if (!data || typeof data.count === 'undefined') return;
                    syncNotificationBadge(Number(data.count) || 0);
                })
                .catch(() => {});
            }

            setInterval(refreshUnreadCount, 60000);
        });
    </script>
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-300 antialiased font-sans transition-colors duration-300">
    @php
        // Access Level Logic (Restored from menu.blade.php)
        $userId = session('id');
        $user = DB::table('users')->where('id', $userId)->first();
        $level = $user ? $user->level_id : 0;

        // Fetch App Settings
        $appSetting = \App\Models\Setting::first();
        $appName = ($appSetting && !empty($appSetting->site_name))
            ? $appSetting->site_name
            : config('app.name', 'Library System');
        $appLogo = $appSetting && $appSetting->logo ? Storage::url($appSetting->logo) : null;
        $appFooterText = $appSetting && $appSetting->footer_text
            ? $appSetting->footer_text
            : '© '.date('Y').' '.$appName.'. All rights reserved.';
        $appBackgroundImageUrl = $appSetting && $appSetting->background_image
            ? Storage::url($appSetting->background_image)
            : null;
        $appBackgroundColor = $appSetting->app_background_color ?? '#f8fafc';
        $appSidebarBgColor = $appSetting->sidebar_bg_color ?? '#0f172a';
        $appTopbarBgColor = $appSetting->topbar_bg_color ?? '#ffffff';
        $appOverlayOpacity = isset($appSetting->background_overlay_opacity)
            ? max(0, min(1, (float) $appSetting->background_overlay_opacity))
            : 0.88;

        $hex = ltrim($appBackgroundColor, '#');
        if (!preg_match('/^[A-Fa-f0-9]{6}$/', $hex)) {
            $hex = 'f8fafc';
        }
        $overlayRgb = [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];

        $notifications = collect();
        $unreadNotificationCount = 0;
        if ($userId && \Illuminate\Support\Facades\Schema::hasTable('notifications')) {
            $notifications = DB::table('notifications')
                ->where('user_id', $userId)
                ->orderByDesc('created_at')
                ->limit(8)
                ->get();

            $unreadNotificationCount = DB::table('notifications')
                ->where('user_id', $userId)
                ->where('is_read', false)
                ->count();
        }
    @endphp

    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        <aside class="app-sidebar w-64 border-r flex-col fixed h-full z-30 hidden md:flex transition-colors duration-300" style="background-color: {{ $appSidebarBgColor }};">
            <!-- Logo -->
            <div class="h-24 flex items-center justify-center border-b sidebar-border px-4">
                <div class="flex items-center gap-3 font-bold text-xl text-slate-800 dark:text-white">
                    @if($appLogo)
                        <img src="{{ $appLogo }}" alt="Logo" class="w-14 h-14 object-contain rounded-lg">
                        <span>{{ $appName }}</span>
                    @else
                        <div class="w-14 h-14 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span>{{ $appName }}</span>
                    @endif
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                
                <!-- Dashboard (All Users) -->
                <a href="{{ url('/home') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('home') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    {{ __('Dashboard') }}
                </a>

                <!-- 1. DATA MASTER -->
                @if(
                    app(\App\Helpers\PermissionHelper::class)->hasPermission('buku.read') ||
                    app(\App\Helpers\PermissionHelper::class)->hasPermission('buku-masuk.read') ||
                    app(\App\Helpers\PermissionHelper::class)->hasPermission('peminjaman.read')
                )
                <div class="pt-4 pb-1 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Master Data') }}</div>

                @if(app(\App\Helpers\PermissionHelper::class)->hasPermission('buku.read'))
                <a href="{{ url('/databuku') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('databuku*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    {{ __('Book Data') }}
                </a>
                @endif

                @if(app(\App\Helpers\PermissionHelper::class)->hasPermission('buku.read'))
                <a href="{{ url('/master-data') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('master-data*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    {{ __('Master Data') }}
                </a>
                @endif

                @if(app(\App\Helpers\PermissionHelper::class)->hasPermission('buku-masuk.read'))
                <a href="{{ url('/datamasuk') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('datamasuk*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    {{ __('Incoming Books') }}
                </a>
                @endif

                @if(app(\App\Helpers\PermissionHelper::class)->hasPermission('peminjaman.read'))
                <a href="{{ url('/peminjaman') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('peminjaman*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('Loans') }}
                </a>
                @endif

                @if(app(\App\Helpers\PermissionHelper::class)->hasPermission('buku.create'))
                <a href="{{ url('/admin/request-buku') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('admin/request-buku*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    {{ __('Request Approval') }}
                </a>
                @endif
                @endif

                <!-- 2. DATA USER -->
                @if(app(\App\Helpers\PermissionHelper::class)->hasPermission('user.read'))
                <a href="{{ url('/datauser') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('datauser*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    {{ __('User Data') }}
                </a>
                @endif

                <!-- 3. LAPORAN -->
                @if(app(\App\Helpers\PermissionHelper::class)->hasPermission('laporan.read'))
                <div class="pt-4 pb-1 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Reports') }}</div>

                <a href="{{ url('/laporanpeminjaman') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('laporanpeminjaman*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('Loan Report') }}
                </a>

                <a href="{{ url('/laporanmasuk') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('laporanmasuk*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('Incoming Books Report') }}
                </a>

                @if($level == 5 || $level == 6)
                <a href="{{ url('/admin/reports') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('admin/reports*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    {{ __('Problem Reports') }}
                </a>
                @endif
                @endif

                <!-- 4. MENU ANGGOTA (Peminjam: 3, Super Admin: 5/6) -->
                @if($level == 3 || $level == 5 || $level == 6)
                <div class="pt-4 pb-1 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Members') }}</div>

                <a href="{{ url('/koleksi') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('koleksi*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    {{ __('Book Collection') }}
                </a>

                <a href="{{ url('/riwayat') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('riwayat*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Loan History') }}
                </a>

                <a href="{{ url('/request-buku') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('request-buku*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    {{ __('Request New Book') }}
                </a>

                <a href="{{ url('/report-problem') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('report-problem*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    {{ __('Report Problem') }}
                </a>
                @endif

                <!-- 5. PENGATURAN (KHUSUS Super Admin: 5/6) -->
                @if($level == 5 || $level == 6)
                <div class="pt-4 pb-1 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('System') }}</div>
                 
                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ __('Role Settings') }}
                </a>

                <a href="{{ route('app_settings.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('app_settings.*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    {{ __('App Settings') }}
                </a>

                <!-- Backup & Reset DB -->
                <a href="{{ url('/system-maintenance') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('system-maintenance*') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                    </svg>
                    {{ __('Database & Backup') }}
                </a>

                <!-- Activity Log (Semua Level Bisa Lihat Punya Sendiri) -->
                <a href="{{ route('activity.log') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('activity.log') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    {{ __('Activity Log') }}
                </a>
                @endif
                
                <!-- Peminjam/Anggota juga bisa lihat log aktivitas -->
                @if($level == 3)
                <div class="pt-4 pb-1 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Account') }}</div>
                <a href="{{ route('activity.log') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('activity.log') ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    {{ __('My Activity') }}
                </a>
                @endif
            </nav>

            <!-- User Profile (Bottom) -->
            <div class="border-t sidebar-border p-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile') }}" class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-800 dark:text-white font-bold hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                        {{ substr(session('name') ?? 'U', 0, 1) }}
                    </a>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('profile') }}" class="text-sm font-medium text-slate-800 dark:text-white truncate hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors">{{ session('name') ?? 'Guest' }}</a>
                    </div>
                    <a href="{{ url('/logout') }}" class="text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors" title="{{ __('Logout') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col md:ml-64 relative transition-colors duration-300 min-h-screen">
            
            <!-- Top Header -->
            <header class="h-16 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-6 z-20 sticky top-0 transition-colors duration-300">
                <!-- Search -->
                <div class="flex-1 max-w-lg relative">
                    <form action="{{ route('search.global') }}" method="GET" class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input id="globalSearchInput" type="text" name="q" value="{{ request('q') }}" class="w-full bg-slate-100 dark:bg-slate-800 border-none rounded-lg pl-10 pr-10 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 transition-colors" placeholder="{{ __('Search global...') }}" autocomplete="off">
                        
                        <!-- Voice Search Button -->
                        <button type="button" id="voiceSearchBtn" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-indigo-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                        </button>
                    </form>
                    
                    <!-- Live Search Results -->
                    <div id="globalSearchResults" class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden z-50 hidden">
                        <!-- Results injected via JS -->
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4 ml-4">
                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleTheme()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none" title="{{ __('Toggle Theme') }}">
                        <!-- Sun Icon (Show in Dark Mode) -->
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <!-- Moon Icon (Show in Light Mode) -->
                        <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <!-- Language Switcher (JSON Based) -->
                    <div id="notificationDropdown" class="relative group">
                        <!-- Trigger Button -->
                        <button class="flex items-center gap-1 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors py-2 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.204 12.596a.3.3 0 01-.24.496H7.964a.3.3 0 01-.27-.17l-1.59-3.18m-1.59-3.18a4.5 4.5 0 00-1.72-3.186m14.39 3.186a4.5 4.5 0 01-1.72 3.186m-7.64 3.186c.616-1.544 1.51-2.912 2.62-4.048m0 0c.932-1.136 1.708-2.38 2.296-3.696M12 21h8m-2-5l2 5-5-2"></path></svg>
                            <span class="uppercase text-xs font-bold">{{ app()->getLocale() }}</span>
                        </button>
                        
                        <!-- Invisible Bridge -->
                        <div class="absolute right-0 top-full h-2 w-full bg-transparent"></div>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 top-[calc(100%+0.5rem)] w-32 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden hidden group-hover:block z-50">
                            <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white {{ app()->getLocale() == 'en' ? 'bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white' : '' }}">English</a>
                            <a href="{{ route('lang.switch', 'id') }}" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white {{ app()->getLocale() == 'id' ? 'bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white' : '' }}">Indonesia</a>
                        </div>
                    </div>

                    <!-- Notifications Dropdown -->
                    <div class="relative group">
                        <button class="relative p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if($unreadNotificationCount > 0)
                            <span id="notificationBadge" class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 text-[10px] leading-[18px] text-center font-semibold text-white bg-red-500 rounded-full">
                                {{ $unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount }}
                            </span>
                            @endif
                        </button>
                        
                        <!-- Invisible Bridge -->
                        <div class="absolute right-0 top-full h-2 w-full bg-transparent"></div>

                        <!-- Dropdown Content -->
                        <div class="absolute right-0 top-[calc(100%+0.5rem)] w-80 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden hidden group-hover:block z-50">
                            <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="font-semibold text-slate-800 dark:text-white">{{ __('Notifications') }}</h3>
                                    @if($notifications->count() > 0 && $unreadNotificationCount > 0)
                                    <form action="{{ route('notifications.read_all') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ __('Mark all as read') }}
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse($notifications as $notification)
                                <a href="{{ route('notifications.read', ['id' => $notification->id, 'redirect' => $notification->url ?: url()->current()]) }}"
                                   class="block p-4 border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors {{ $notification->is_read ? 'opacity-80' : '' }}">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-sm text-slate-800 dark:text-white font-medium">{{ $notification->title }}</p>
                                        @if(!$notification->is_read)
                                        <span class="mt-1 inline-block w-2 h-2 rounded-full bg-indigo-500"></span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">{{ $notification->message }}</p>
                                    <p class="text-[11px] text-slate-400 mt-2">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                                </a>
                                @empty
                                <div class="p-4 text-center text-sm text-slate-500 italic">
                                    {{ __('No notifications yet.') }}
                                </div>
                                @endforelse
                            </div>
                            <div class="p-3 bg-slate-50 dark:bg-slate-700/50 text-center text-xs text-slate-500">
                                {{ __('Notification updates are shown for your account.') }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Menu Button (Visible only on small screens) -->
                    <button class="md:hidden p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden bg-slate-50 dark:bg-slate-900 p-6 md:p-8 transition-colors duration-300"
                @if($appBackgroundImageUrl)
                    style="background-image:linear-gradient(rgba({{ $overlayRgb[0] }},{{ $overlayRgb[1] }},{{ $overlayRgb[2] }},{{ $appOverlayOpacity }}), rgba({{ $overlayRgb[0] }},{{ $overlayRgb[1] }},{{ $overlayRgb[2] }},{{ $appOverlayOpacity }})), url('{{ $appBackgroundImageUrl }}'); background-size:cover; background-position:center; background-attachment:fixed;"
                @endif
            >
                @if(session('message'))
                <div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-between">
                    <span>{{ session('message') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-300">&times;</button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-300">&times;</button>
                </div>
                @endif

                @yield('content')
            </main>

            <footer class="border-t border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 px-6 py-3 text-center text-sm text-slate-500 dark:text-slate-400">
                {{ $appFooterText }}
            </footer>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nav = document.querySelector('aside nav');
            if (!nav) return;

            const headerSelector = 'div.pt-4.pb-1.px-3.text-xs.font-semibold.text-slate-500.uppercase.tracking-wider';
            const headers = Array.from(nav.querySelectorAll(headerSelector));

            headers.forEach((header, index) => {
                const toggle = document.createElement('button');
                toggle.type = 'button';
                toggle.className = 'js-nav-group-toggle w-full flex items-center justify-between px-3 pt-4 pb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider hover:text-slate-700 dark:hover:text-slate-300 transition-colors';
                toggle.innerHTML = `
                    <span>${header.textContent.trim()}</span>
                    <svg class="js-nav-chevron w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                `;

                const body = document.createElement('div');
                body.className = 'js-nav-group-body space-y-1';
                body.dataset.groupIndex = String(index);

                let node = header.nextElementSibling;
                while (node && !node.matches(headerSelector)) {
                    const next = node.nextElementSibling;
                    body.appendChild(node);
                    node = next;
                }

                const hasActiveLink = !!body.querySelector('a.bg-indigo-600');
                const openByDefault = hasActiveLink;
                body.classList.toggle('hidden', !openByDefault);
                toggle.setAttribute('aria-expanded', openByDefault ? 'true' : 'false');

                const chevron = toggle.querySelector('.js-nav-chevron');
                if (!openByDefault && chevron) {
                    chevron.classList.add('-rotate-90');
                }

                toggle.addEventListener('click', () => {
                    const isHidden = body.classList.toggle('hidden');
                    toggle.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
                    chevron?.classList.toggle('-rotate-90', isHidden);
                });

                header.replaceWith(toggle);
                toggle.insertAdjacentElement('afterend', body);
            });
        });
    </script>
    <script src="{{ asset('js/global-table-filter.js') }}"></script>
</body>
</html>
