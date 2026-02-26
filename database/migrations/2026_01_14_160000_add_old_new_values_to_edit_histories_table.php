<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('edit_histories', function (Blueprint $table) {
            $table->text('old_values')->nullable()->after('perubahan');
            $table->text('new_values')->nullable()->after('old_values');
        });
    }

    public function down(): void
    {
        Schema::table('edit_histories', function (Blueprint $table) {
            $table->dropColumn('old_values');
            $table->dropColumn('new_values');
        });
    }
};

