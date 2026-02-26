@php
    // Ambil ID user dari session
    $userId = session('id');

    // Cari data user untuk dapatkan level_id nya
    $user = DB::table('users')->where('id', $userId)->first();

    // Ambil level_id (Kalau user tidak ketemu, set 0)
    $level = $user ? $user->level_id : 0;
@endphp

    <header class="main-header">
        <div class="container navbar-content">
            <a class="navbar-brand" href="/home" style="display: flex; align-items: center; text-decoration: none;">
                @if(isset($globalSettings) && $globalSettings->logo)
                    <img src="{{ Storage::url($globalSettings->logo) }}" alt="Logo" style="height: 50px; margin-right: 10px;">
                @else
                    <img src="{{ asset('images/Asset1.png') }}" alt="Logo" style="height: 50px; margin-right: 10px;">
                @endif
                
                @if(isset($globalSettings) && $globalSettings->site_name)
                    <span style="font-size: 1.25rem; font-weight: bold; color: white;">{{ $globalSettings->site_name }}</span>
                @endif
            </a>

            <div style="display: flex; align-items: center;">
                <nav class="nav-desktop">

                    {{-- ========================================================= --}}
                    {{-- 1. DATA MASTER (Admin: 1, Petugas: 2, Super Admin: 5/6)   --}}
                    {{-- ========================================================= --}}
                    @if($level == 1 || $level == 2 || $level == 5 || $level == 6)
                    <div class="nav-item dropdown">
                        <button class="dropdown-btn">
                            Data Master
                            <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="dropdown-content">
                            <a href="/databuku">Data Buku</a>
                            <a href="/datamasuk">Data Buku Masuk</a>
                            <a href="/peminjaman">Data Peminjaman</a>
                        </div>
                    </div>
                    @endif

                    {{-- ========================================================= --}}
                    {{-- 2. DATA USER (Admin: 1, Super Admin: 5/6)                 --}}
                    {{-- ========================================================= --}}
                    @if($level == 1 || $level == 5 || $level == 6)
                    <a href="/datauser" class="nav-link">Data User</a>
                    @endif

                    {{-- ========================================================= --}}
                    {{-- 3. LAPORAN (Admin: 1, Pemilik: 4, Super Admin: 5/6)       --}}
                    {{-- ========================================================= --}}
                    @if($level == 1 || $level == 4 || $level == 5 || $level == 6)
                    <div class="nav-item dropdown">
                        <button class="dropdown-btn">
                            Laporan
                            <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="dropdown-content">
                            <a href="/laporanpeminjaman">Laporan Peminjaman</a>
                            <a href="/laporanmasuk">Laporan Buku Masuk</a>
                        </div>
                    </div>
                    @endif

                    {{-- ========================================================= --}}
                    {{-- 4. MENU ANGGOTA (Peminjam: 3)                             --}}
                    {{-- ========================================================= --}}
                    @if($level == 3)
                    <a href="/koleksi" class="nav-link">📚 Koleksi Buku</a>
                    <a href="/riwayat" class="nav-link">🕒 Riwayat Peminjaman</a>
                    @endif

                    {{-- ========================================================= --}}
                    {{-- 5. PENGATURAN (KHUSUS Super Admin: 5/6)                   --}}
                    {{-- ========================================================= --}}
                    @if($level == 5 || $level == 6)
                    <div class="nav-item dropdown">
                        <button class="dropdown-btn" style="color: #ffc107; font-weight: bold;">
                            ⚙️ Pengaturan
                            <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="dropdown-content">
                            {{-- Link ke Route yang kita buat tadi --}}
                            <a href="{{ route('settings.index') }}">Hak Akses Role</a>
                            <a href="{{ route('app_settings.index') }}">Pengaturan Aplikasi</a>
                        </div>
                    </div>
                    @endif

                </nav>

                <a href="/logout" class="btn-logout-custom">Logout</a>
            </div>
            
            <button id="menu-toggle-btn" class="menu-toggle-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>
        
        <div id="nav-mobile" class="nav-mobile" style="display: none;">

            @if($level == 1 || $level == 2 || $level == 5)
            <div class="nav-mobile-group">
                <p>Data Master</p>
                <a href="/databuku">Data Buku</a>
                <a href="/datamasuk">Data Buku Masuk</a>
                <a href="/peminjaman">Data Peminjaman</a>
            </div>
            @endif

            @if($level == 1 || $level == 5)
            <div class="nav-mobile-group">
                <a href="/datauser">Data User</a>
            </div>
            @endif

            @if($level == 1 || $level == 4 || $level == 5)
            <div class="nav-mobile-group">
                <p>Laporan</p>
                <a href="/laporanpeminjaman">Laporan Peminjaman</a>
                <a href="/laporanmasuk">Laporan Buku Masuk</a>
            </div>
            @endif

            @if($level == 5)
            <div class="nav-mobile-group">
                <p>Pengaturan</p>
                <a href="{{ route('settings.index') }}">⚙️ Atur Hak Akses</a>
            </div>
            @endif

            @if($level == 3)
            <div class="nav-mobile-group">
                <a href="/koleksi">📚 Koleksi Buku</a>
                <a href="/riwayat">🕒 Riwayat Peminjaman</a>
            </div>
            @endif

        </div>
    </header>