<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('settings', 'theme_primary_color')) {
                $table->string('theme_primary_color', 20)->nullable()->after('contact_info');
            }

            if (! Schema::hasColumn('settings', 'theme_secondary_color')) {
                $table->string('theme_secondary_color', 20)->nullable()->after('theme_primary_color');
            }

            if (! Schema::hasColumn('settings', 'footer_text')) {
                $table->text('footer_text')->nullable()->after('theme_secondary_color');
            }

            if (! Schema::hasColumn('settings', 'background_image')) {
                $table->string('background_image')->nullable()->after('footer_text');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $dropColumns = [];

            foreach (['theme_primary_color', 'theme_secondary_color', 'footer_text', 'background_image'] as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if (! empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};

