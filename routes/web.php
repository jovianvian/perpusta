<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MyController;
use App\Http\Controllers\RoleSettingController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ReportProblemController;
use App\Http\Controllers\NotificationController;

// AUTHENTICATION ROUTES
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// PASSWORD RESET
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPasswordHandler'])->name('password.update');
Route::get('/password/otp', [AuthController::class, 'showOtpForm'])->name('password.otp');
Route::post('/password/otp', [AuthController::class, 'verifyOtp'])->name('password.verify_otp');

// EMAIL VERIFICATION
Route::get('/verification', function () { return view('emailverification'); })->name('verification.form');
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');
Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('resend.verification');

// LANGUAGE SWITCHER
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id', 'jv'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// PROTECTED ROUTES
Route::group(['middleware' => 'web'], function () {
    
    // DASHBOARD & COMMON
    Route::get('/home', [MyController::class, 'home']); // Home masih di MyController (untuk saat ini)
    Route::get('/search', [MyController::class, 'search'])->name('search.global');
    Route::post('/update-location', [LogController::class, 'updateLocation'])->name('update.location');
    
    // PROFILE
    Route::get('/profile', [MyController::class, 'profile'])->name('profile');
    Route::post('/profile', [MyController::class, 'updateProfile'])->name('profile.update');
    Route::get('/notifications/read/{id}', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read_all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread_count');

    // REPORT PROBLEM
    Route::get('/report-problem', [ReportProblemController::class, 'create'])->name('report.create');
    Route::post('/report-problem', [ReportProblemController::class, 'store'])->name('report.store');
    Route::get('/admin/reports', [ReportProblemController::class, 'index'])->name('report.index');
    Route::post('/admin/reports/{id}', [ReportProblemController::class, 'update'])->name('report.update');

    // DATA BUKU
    Route::get('/databuku', [BookController::class, 'books'])->middleware('permission:buku.read');
    Route::post('/databuku/store', [BookController::class, 'simpant'])->middleware('permission:buku.create');
    Route::post('/databuku/update/{id}', [BookController::class, 'simpane'])->middleware('permission:buku.update');
    Route::get('/databuku/delete/{id}', [BookController::class, 'delete'])->middleware('permission:buku.delete');
    Route::post('/databuku/import', [BookController::class, 'import'])->name('books.import'); // New Route
    
    // MASTER DATA (Unified)
    Route::get('/master-data', [MasterDataController::class, 'index'])->middleware('permission:buku.read');
    
    // PENULIS
    Route::post('/master/penulis', [MasterDataController::class, 'storePenulis'])->middleware('permission:buku.create');
    Route::post('/master/penulis/{id}', [MasterDataController::class, 'updatePenulis'])->middleware('permission:buku.update');
    Route::get('/master/penulis/delete/{id}', [MasterDataController::class, 'deletePenulis'])->middleware('permission:buku.delete');

    // KATEGORI
    Route::post('/master/kategori', [MasterDataController::class, 'storeKategori'])->middleware('permission:buku.create');
    Route::post('/master/kategori/{id}', [MasterDataController::class, 'updateKategori'])->middleware('permission:buku.update');
    Route::get('/master/kategori/delete/{id}', [MasterDataController::class, 'deleteKategori'])->middleware('permission:buku.delete');

    // PENERBIT
    Route::post('/master/penerbit', [MasterDataController::class, 'storePenerbit'])->middleware('permission:buku.create');
    Route::post('/master/penerbit/{id}', [MasterDataController::class, 'updatePenerbit'])->middleware('permission:buku.update');
    Route::get('/master/penerbit/delete/{id}', [MasterDataController::class, 'deletePenerbit'])->middleware('permission:buku.delete');

    // DATA BUKU MASUK
    Route::get('/datamasuk', [BookController::class, 'dataMasukBuku'])->middleware('permission:buku-masuk.read');
    Route::post('/datamasuk/store', [BookController::class, 'simpanDataMasuk'])->middleware('permission:buku-masuk.create');
    Route::put('/datamasuk/update/{id}', [BookController::class, 'updateDataMasuk'])->middleware('permission:buku-masuk.update');
    Route::get('/datamasuk/hapus/{id}', [BookController::class, 'hapusDataMasuk'])->middleware('permission:buku-masuk.delete');

    // PEMINJAMAN (ADMIN)
    Route::get('/peminjaman', [LoanController::class, 'peminjaman'])->middleware('permission:peminjaman.read');
    Route::post('/peminjaman/store', [LoanController::class, 'simpanPeminjaman'])->middleware('permission:peminjaman.create');
    Route::post('/peminjaman/update', [LoanController::class, 'updatePeminjaman'])->middleware('permission:peminjaman.update');
    Route::get('/peminjaman/hapus/{id}', [LoanController::class, 'hapusPeminjaman'])->middleware('permission:peminjaman.delete');
    Route::get('/peminjaman/kembalikan/{id}', [LoanController::class, 'kembalikanPeminjaman'])->middleware('permission:peminjaman.update');
    Route::get('/peminjaman/konfirmasi/{id}/{aksi}', [LoanController::class, 'konfirmasiPeminjaman'])->middleware('permission:peminjaman.approve');

    // DATA USER
    Route::get('/datauser', [UserController::class, 'dataUser'])->middleware('permission:user.read');
    Route::post('/datauser/store', [UserController::class, 'storeUsers'])->middleware('permission:user.create');
    Route::post('/datauser/update/{id}', [UserController::class, 'updateUser'])->middleware('permission:user.update');
    Route::get('/datauser/delete/{id}', [UserController::class, 'destroy'])->middleware('permission:user.delete');
    Route::get('/datauser/reset/{id}', [UserController::class, 'resetPassword'])->middleware('permission:user.update');

    // LAPORAN
    Route::get('/laporanpeminjaman', [ReportController::class, 'laporanPeminjaman'])->middleware('permission:laporan.read');
    Route::get('/laporanpeminjaman/print', [ReportController::class, 'printPeminjaman'])->middleware('permission:laporan.export');
    Route::get('/laporanpeminjaman/pdf', [ReportController::class, 'pdfPeminjaman'])->middleware('permission:laporan.export');
    Route::get('/laporanpeminjaman/excel', [ReportController::class, 'excelPeminjaman'])->middleware('permission:laporan.export');

    Route::get('/laporanmasuk', [ReportController::class, 'laporanMasuk'])->middleware('permission:laporan.read');
    Route::get('/laporanmasuk/print', [ReportController::class, 'printMasuk'])->middleware('permission:laporan.export');
    Route::get('/laporanmasuk/pdf', [ReportController::class, 'pdfMasuk'])->middleware('permission:laporan.export');
    Route::get('/laporanmasuk/excel', [ReportController::class, 'excelMasuk'])->middleware('permission:laporan.export');

    // LOG & MAINTENANCE
    Route::get('/activity-log', [LogController::class, 'activityLog'])->name('activity.log');
    Route::get('/system-maintenance', [MyController::class, 'maintenance'])->name('system.maintenance');
    Route::get('/system-maintenance/backup', [MyController::class, 'backup'])->name('system.backup');
    Route::post('/system-maintenance/reset', [MyController::class, 'resetDatabase'])->name('system.reset');
    Route::post('/system-maintenance/restore', [MyController::class, 'restoreDatabase'])->name('system.restore');

    // ANGGOTA AREA
    Route::get('/koleksi', [BookController::class, 'koleksiBuku']);
    Route::get('/penulis/{id}', [BookController::class, 'detailPenulis']); // Route Profil Penulis
    Route::get('/riwayat', [LoanController::class, 'riwayatPeminjaman']);
    Route::post('/peminjaman/pinjam/{id}', [LoanController::class, 'pinjamBuku']);
    Route::post('/peminjaman/ajukan-kembali/{id}', [LoanController::class, 'ajukanKembali']);

    // TRASH & REVERT (SUPER ADMIN)
    Route::get('/trash', [MyController::class, 'trash']);
    Route::get('/restore/{type}/{id}', [MyController::class, 'restore']);
    Route::get('/force-delete/{type}/{id}', [MyController::class, 'forceDelete']);
    Route::get('/restore-all/{type}', [MyController::class, 'restoreAll']);
    Route::get('/revert/{id}', [MyController::class, 'revertEdit']);

    // SETTINGS
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', [RoleSettingController::class, 'index'])->name('settings.index');
        Route::get('/{id}/edit', [RoleSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/{id}', [RoleSettingController::class, 'update'])->name('settings.update');
    });
    Route::group(['prefix' => 'app-settings'], function () {
        Route::get('/', [SettingController::class, 'index'])->name('app_settings.index');
        Route::post('/', [SettingController::class, 'update'])->name('app_settings.update');
    });

    // REQUEST BUKU (MEMBER & ADMIN)
    Route::get('/request-buku', [App\Http\Controllers\RequestBookController::class, 'indexMember']);
    Route::post('/request-buku', [App\Http\Controllers\RequestBookController::class, 'store']);
    Route::get('/admin/request-buku', [App\Http\Controllers\RequestBookController::class, 'indexAdmin'])->middleware('permission:buku.create'); // Asumsi admin buku yg urus
    Route::post('/admin/request-buku/{id}', [App\Http\Controllers\RequestBookController::class, 'updateStatus']);

    // API ROUTES (FOR AJAX/DATATABLES)
    Route::get('/api/databuku', [DataController::class, 'books'])->middleware('permission:buku.read');
    Route::get('/api/history/books', [DataController::class, 'historyBooks'])->middleware('permission:buku.update');
    Route::get('/api/datamasuk', [DataController::class, 'dataMasukBuku'])->middleware('permission:buku-masuk.read');
    Route::get('/api/history/datamasuk', [DataController::class, 'historyMasuk'])->middleware('permission:buku-masuk.update');
    Route::get('/api/peminjaman', [DataController::class, 'peminjaman'])->middleware('permission:peminjaman.read');
    Route::get('/api/history/peminjaman', [DataController::class, 'historyPeminjaman'])->middleware('permission:peminjaman.update');
    Route::get('/api/datauser', [DataController::class, 'dataUser'])->middleware('permission:user.read');
    Route::get('/api/history/user', [DataController::class, 'historyUser'])->middleware('permission:user.update');
    Route::get('/api/riwayat', [DataController::class, 'riwayatPeminjaman']);
    Route::get('/riwayat/json', [DataController::class, 'riwayatPeminjaman']); // Fix for riwayat view ajax call
});
