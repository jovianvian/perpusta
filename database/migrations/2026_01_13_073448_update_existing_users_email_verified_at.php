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
        // Update existing users to have email_verified_at set to their created_at date
        // This allows existing users to login without needing to verify their email
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update([
                'email_verified_at' => DB::raw('created_at')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set email_verified_at to null for users that were updated by this migration
        // Note: This is not perfect since we can't distinguish which users were updated
        // But it's a reasonable rollback
        DB::table('users')
            ->whereColumn('email_verified_at', 'created_at')
            ->update([
                'email_verified_at' => null
            ]);
    }
};
