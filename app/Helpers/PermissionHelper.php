<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PermissionHelper
{
    public function hasPermission($permission)
    {
        // Ambil ID dari session (sesuai controller kamu)
        $userId = Session::get('id');
        
        if (!$userId) {
            return false;
        }

        // Ambil level_id user
        $user = DB::table('users')
            ->where('id', $userId)
            ->first();

        if (!$user) {
            return false;
        }

        // 1. BYPASS SUPER ADMIN (LEVEL 5 & 6)
        // Level 5: Super Admin
        // Level 6: Super Admin (Alternative)
        if ($user->level_id == 5 || $user->level_id == 6) {
            return true;
        }

        // 2. CEK PERMISSION DI DATABASE (Untuk Admin, Petugas, dll)
        return DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.level_id', $user->level_id)
            ->where('permissions.name', $permission)
            ->exists();
    }

    public function isSuperAdmin()
    {
        $userId = Session::get('id');
        if (!$userId) return false;

        $user = DB::table('users')->where('id', $userId)->first();
        
        // Cek apakah levelnya 5 atau 6
        return $user && ($user->level_id == 5 || $user->level_id == 6);
    }
}
