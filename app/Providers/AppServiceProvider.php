<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Carbon\Carbon; // <--- 1. Tambah ini
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // <--- 2. Tambah blok ini ---
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        // ---------------------------
        
        Schema::defaultStringLength(191); // (Ini bawaan laravel biasanya)

        // Share settings to all views, but don't block app boot if DB is unavailable.
        try {
            if (Schema::hasTable('settings')) {
                $globalSettings = Setting::first();
                View::share('globalSettings', $globalSettings);
            }
        } catch (Throwable $e) {
            // Skip loading global settings during early setup / DB downtime.
        }
    }
}
