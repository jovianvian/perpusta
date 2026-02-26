<!-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Perpustakaan Satu Pintu</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<!-- LOGIC CLASS BODY: Kalau page login -> auth-page, kalau bukan -> bodymenu -->
<body class="{{ $page == 'login' ? 'auth-page' : 'bodymenu' }}">

    {{-- ================================================================= --}}
    {{-- BAGIAN 1: HALAMAN LOGIN & REGISTER (Muncul jika page == 'login')  --}}
    {{-- ================================================================= --}}
    @if($page == 'login')
        <div id="auth-section">
            <!-- Login Card -->
            <div class="card-unique" id="loginCard">
                <div class="card-header-wave"><h3>Login</h3></div>
                <div class="card-body">
                    <form id="loginForm">
                        @csrf
                        <div class="form-group">
                            <input type="text" id="username" class="form-input" placeholder=" " required>
                            <label class="form-label">Username</label>
                        </div>
                        <div class="form-group">
                            <input type="password" id="password" class="form-input" placeholder=" " required>
                            <label class="form-label">Password</label>
                        </div>
                        <button type="submit" id="loginBtn" class="btn-shine">Login</button>
                    </form>
                </div>
            </div>
        </div>
    @else
    {{-- ================================================================= --}}
    {{-- BAGIAN 2: DASHBOARD UTAMA (Muncul jika SUDAH LOGIN)               --}}
    {{-- ================================================================= --}}
    
        <!-- HEADER & NAVBAR -->
        @include('menu')

        <!-- KONTEN UTAMA -->
        <div class="content-wrapper">
            <div class="container">
                
                {{-- KONTEN: DASHBOARD --}}
                @if($page == 'dashboard')
                    <div class="card-custom" style="text-align:center; padding:50px;">
                        <h1>Selamat Datang, {{ $user->name }}!</h1>
                        <p>Anda login sebagai {{ $user->level_id == 1 ? 'Admin' : 'User' }}</p>
                    </div>
                @endif

                {{-- KONTEN: DATA BUKU --}}
                @if($page == 'buku')
                    <div class="card-custom">
                        <div class="card-header">
                            <h2 class="card-title">Daftar Buku</h2>
                            <button onclick="openAddBookModal()" class="btn-custom btn-primary-custom">+ Tambah Buku</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table-custom">
                                <thead>
                                    <tr><th>No</th><th>Judul</th><th>Penulis</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($dataBuku as $b)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $b->judul }}</td>
                                        <td>{{ $b->nama_penulis }}</td>
                                        <td class="text-center">
                                            <button onclick="openEditBookModal(this)" 
                                                data-id="{{ $b->id }}" data-judul="{{ $b->judul }}"
                                                data-penulis="{{ $b->penulis_id }}" 
                                                data-penerbit="{{ $b->penerbit_id }}"
                                                data-tahun="{{ $b->tahun }}" data-stok="{{ $b->stok }}"
                                                data-kategori_id="{{ $b->kategori_id }}"
                                                class="btn-custom btn-warning-custom">Edit</button>
                                            <a href="/databuku/delete/{{ $b->id }}" class="btn-custom btn-danger-custom btn-delete">Hapus</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- KONTEN: DATA USER --}}
                @if($page == 'user')
                    <div class="card-custom">
                        <div class="card-header">
                            <h2 class="card-title">Data User</h2>
                            <button onclick="openAddUserModal()" class="btn-custom btn-primary-custom">+ Tambah User</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table-custom">
                                <thead>
                                    <tr><th>No</th><th>Nama</th><th>Email</th><th>Level</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($dataUser as $u)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>{{ $u->nama_level }}</td>
                                        <td class="text-center">
                                            <button onclick="openEditUserModal(this)" 
                                                data-id="{{ $u->id }}" data-name="{{ $u->name }}"
                                                data-email="{{ $u->email }}" data-level_id="{{ $u->level_id }}"
                                                class="btn-custom btn-warning-custom">Edit</button>
                                            <a href="/datauser/delete/{{ $u->id }}" class="btn-custom btn-danger-custom btn-delete">Hapus</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- KONTEN: PEMINJAMAN (dan halaman lain) --}}
                @if($page == 'peminjaman')
                    <!-- ... Copas Tabel Peminjaman Disini ... -->
                @endif

            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- BAGIAN 3: SEMUA MODAL (Ditaruh di paling bawah body)              --}}
        {{-- ================================================================= --}}
        
        <!-- MODAL BUKU -->
        <div id="bookModal" class="modal-overlay" style="display: none;">
            <div class="modal-container">
                <div class="modal-header">
                    <h3 id="bookModalTitle" class="modal-title">Buku</h3>
                    <button onclick="closeBookModal()" class="btn-close-modal">&times;</button>
                </div>
                <form id="bookModalForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <div id="bookMethodContainer"></div>
                    <div class="modal-body">
                        <!-- Input Judul -->
                        <div class="form-group"><label>Judul</label><input type="text" name="judul" id="bookJudul" class="form-input"></div>
                        <!-- ... Input lainnya (Penulis, Penerbit Select Option pakai $masterPenulis dll) ... -->
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-custom btn-primary-custom">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL USER -->
        <div id="userModal" class="modal-overlay" style="display: none;">
            <!-- ... Copas isi Modal User disini ... -->
        </div>

        <!-- MODAL PESAN (ALERT) -->
        <div id="popupModal" class="modal-overlay" style="display: none;">
            <div class="modal-box">
                <h4 id="modalText">Pesan</h4>
                <button id="closeModal" class="modal-btn">OK</button>
            </div>
        </div>

    @endif <!-- End if Page != Login -->

    <!-- SCRIPT -->
    <script src="{{ asset('js/js.js') }}"></script>

</body>
</html> -->