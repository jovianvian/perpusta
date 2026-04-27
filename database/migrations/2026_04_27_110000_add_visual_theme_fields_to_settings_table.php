<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('settings', 'app_background_color')) {
                $table->string('app_background_color', 20)->nullable()->after('theme_secondary_color');
            }

            if (! Schema::hasColumn('settings', 'background_overlay_opacity')) {
                $table->decimal('background_overlay_opacity', 3, 2)->nullable()->after('app_background_color');
            }

            if (! Schema::hasColumn('settings', 'sidebar_bg_color')) {
                $table->string('sidebar_bg_color', 20)->nullable()->after('background_overlay_opacity');
            }

            if (! Schema::hasColumn('settings', 'topbar_bg_color')) {
                $table->string('topbar_bg_color', 20)->nullable()->after('sidebar_bg_color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $dropColumns = [];
            foreach ([
                'app_background_color',
                'background_overlay_opacity',
                'sidebar_bg_color',
                'topbar_bg_color',
            ] as $column) {
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

