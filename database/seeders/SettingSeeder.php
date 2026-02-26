<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
            ['id' => 1],
            [
                'site_name' => 'Perpustakaan Digital',
                'manager_name' => 'Admin Utama',
                'address' => 'Jalan Pustaka No. 123, Jakarta',
                'contact_info' => '021-12345678',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
