<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE peminjaman_buku MODIFY COLUMN status ENUM('pending_pinjam', 'dipinjam', 'pending_kembali', 'dikembalikan') DEFAULT 'pending_pinjam'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_buku', function (Blueprint $table) {
            //
        });
    }
};
