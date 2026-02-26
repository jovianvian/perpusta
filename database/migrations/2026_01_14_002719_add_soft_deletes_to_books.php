<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('books', function ($table) {
            $table->softDeletes();
        });
        Schema::table('peminjaman_buku', function ($table) {
            $table->softDeletes();
        });
        Schema::table('users', function ($table) {
            $table->softDeletes();
        });
        Schema::table('data_masuk_buku', function ($table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('books', function ($table) {
            $table->dropSoftDeletes();
        });
        Schema::table('peminjaman_buku', function ($table) {
            $table->dropSoftDeletes();
        });
        Schema::table('users', function ($table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_masuk_buku', function ($table) {
            $table->dropSoftDeletes();
        });
    }
};
