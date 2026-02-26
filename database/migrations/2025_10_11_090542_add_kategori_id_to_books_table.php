<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'kategori_id')) {
                $table->unsignedBigInteger('kategori_id')->nullable()->after('penerbit_id');
                $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'kategori_id')) {
                $table->dropForeign(['kategori_id']);
                $table->dropColumn('kategori_id');
            }
        });
    }
};
