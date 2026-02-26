<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id(); 
            $table->string('judul');
            $table->unsignedBigInteger('penulis_id');
            $table->unsignedBigInteger('penerbit_id');
            $table->year('tahun');
            $table->integer('stok'); 
            $table->timestamps();

            $table->foreign('penulis_id')->references('id')->on('penulis')->onDelete('cascade');
            $table->foreign('penerbit_id')->references('id')->on('penerbit')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

