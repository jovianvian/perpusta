<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert Super Admin level if not exists
        if (!DB::table('levels')->where('nama_level', 'Super Admin')->exists()) {
            DB::table('levels')->insert([
                'nama_level' => 'Super Admin',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('levels')->where('nama_level', 'Super Admin')->delete();
    }
};
