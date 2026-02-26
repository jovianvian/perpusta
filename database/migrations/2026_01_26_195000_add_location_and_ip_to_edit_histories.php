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
        Schema::table('edit_histories', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('edited_by');
            $table->string('user_agent')->nullable()->after('ip_address');
            $table->decimal('latitude', 10, 8)->nullable()->after('user_agent');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('action_type')->nullable()->after('table_name'); // create, update, delete, login, logout
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('edit_histories', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'user_agent', 'latitude', 'longitude', 'action_type']);
        });
    }
};
