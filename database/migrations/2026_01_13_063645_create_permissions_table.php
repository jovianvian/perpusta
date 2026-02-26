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
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique(); // contoh: buku.create, buku.read, buku.update, buku.delete
                $table->string('slug')->unique(); // contoh: buku-create, buku-read
                $table->string('module'); // contoh: buku, peminjaman, user, laporan
                $table->string('action'); // contoh: create, read, update, delete
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
