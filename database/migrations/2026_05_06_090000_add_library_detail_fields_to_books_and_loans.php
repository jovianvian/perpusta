<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'isbn')) {
                $table->string('isbn', 32)->nullable()->after('judul');
            }
            if (!Schema::hasColumn('books', 'nomor_buku')) {
                $table->string('nomor_buku', 64)->nullable()->after('isbn');
            }
            if (!Schema::hasColumn('books', 'barcode')) {
                $table->string('barcode', 64)->nullable()->after('nomor_buku');
            }
            if (!Schema::hasColumn('books', 'rak_kategori')) {
                $table->string('rak_kategori', 64)->nullable()->after('kategori_id');
            }
            if (!Schema::hasColumn('books', 'rak_lokasi')) {
                $table->string('rak_lokasi', 128)->nullable()->after('rak_kategori');
            }
            if (!Schema::hasColumn('books', 'bahasa')) {
                $table->string('bahasa', 32)->nullable()->after('tahun');
            }
            if (!Schema::hasColumn('books', 'jumlah_halaman')) {
                $table->unsignedInteger('jumlah_halaman')->nullable()->after('bahasa');
            }
            if (!Schema::hasColumn('books', 'kondisi_buku')) {
                $table->string('kondisi_buku', 32)->nullable()->after('stok');
            }
        });

        try {
            DB::statement("ALTER TABLE books ADD UNIQUE INDEX books_barcode_unique (barcode)");
        } catch (\Throwable $e) {
            // ignore if already exists
        }

        try {
            DB::statement("ALTER TABLE books ADD UNIQUE INDEX books_nomor_buku_unique (nomor_buku)");
        } catch (\Throwable $e) {
            // ignore if already exists
        }

        Schema::table('peminjaman_buku', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman_buku', 'transaction_type')) {
                $table->string('transaction_type', 32)->default('pinjam')->after('status');
            }
            if (!Schema::hasColumn('peminjaman_buku', 'barcode_scanned')) {
                $table->string('barcode_scanned', 64)->nullable()->after('transaction_type');
            }
            if (!Schema::hasColumn('peminjaman_buku', 'catatan')) {
                $table->text('catatan')->nullable()->after('denda');
            }
        });

        try {
            DB::statement("ALTER TABLE peminjaman_buku MODIFY COLUMN status ENUM('pending_pinjam','dipinjam','pending_kembali','dikembalikan','rusak','hilang','baca_di_tempat') DEFAULT 'pending_pinjam'");
        } catch (\Throwable $e) {
            // keep existing enum if DB engine rejects
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE peminjaman_buku MODIFY COLUMN status ENUM('pending_pinjam','dipinjam','pending_kembali','dikembalikan','rusak','hilang') DEFAULT 'pending_pinjam'");
        } catch (\Throwable $e) {
            // ignore
        }

        Schema::table('peminjaman_buku', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_buku', 'catatan')) {
                $table->dropColumn('catatan');
            }
            if (Schema::hasColumn('peminjaman_buku', 'barcode_scanned')) {
                $table->dropColumn('barcode_scanned');
            }
            if (Schema::hasColumn('peminjaman_buku', 'transaction_type')) {
                $table->dropColumn('transaction_type');
            }
        });

        Schema::table('books', function (Blueprint $table) {
            foreach ([
                'kondisi_buku',
                'jumlah_halaman',
                'bahasa',
                'rak_lokasi',
                'rak_kategori',
                'barcode',
                'nomor_buku',
                'isbn',
            ] as $column) {
                if (Schema::hasColumn('books', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

