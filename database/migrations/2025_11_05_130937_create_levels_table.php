<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('level_id')->nullable()->after('password');
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');
        });
    }
};
