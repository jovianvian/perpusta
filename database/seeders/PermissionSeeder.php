<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions untuk setiap modul
        $permissions = [
            // BUKU MODULE
            ['name' => 'buku.create', 'slug' => 'buku-create', 'module' => 'buku', 'action' => 'create', 'description' => 'Tambah data buku'],
            ['name' => 'buku.read', 'slug' => 'buku-read', 'module' => 'buku', 'action' => 'read', 'description' => 'Lihat data buku'],
            ['name' => 'buku.update', 'slug' => 'buku-update', 'module' => 'buku', 'action' => 'update', 'description' => 'Edit data buku'],
            ['name' => 'buku.delete', 'slug' => 'buku-delete', 'module' => 'buku', 'action' => 'delete', 'description' => 'Hapus data buku'],
            
            // BUKU MASUK MODULE
            ['name' => 'buku-masuk.create', 'slug' => 'buku-masuk-create', 'module' => 'buku-masuk', 'action' => 'create', 'description' => 'Tambah data buku masuk'],
            ['name' => 'buku-masuk.read', 'slug' => 'buku-masuk-read', 'module' => 'buku-masuk', 'action' => 'read', 'description' => 'Lihat data buku masuk'],
            ['name' => 'buku-masuk.update', 'slug' => 'buku-masuk-update', 'module' => 'buku-masuk', 'action' => 'update', 'description' => 'Edit data buku masuk'],
            ['name' => 'buku-masuk.delete', 'slug' => 'buku-masuk-delete', 'module' => 'buku-masuk', 'action' => 'delete', 'description' => 'Hapus data buku masuk'],
            
            // PEMINJAMAN MODULE
            ['name' => 'peminjaman.create', 'slug' => 'peminjaman-create', 'module' => 'peminjaman', 'action' => 'create', 'description' => 'Tambah data peminjaman'],
            ['name' => 'peminjaman.read', 'slug' => 'peminjaman-read', 'module' => 'peminjaman', 'action' => 'read', 'description' => 'Lihat data peminjaman'],
            ['name' => 'peminjaman.update', 'slug' => 'peminjaman-update', 'module' => 'peminjaman', 'action' => 'update', 'description' => 'Edit data peminjaman'],
            ['name' => 'peminjaman.delete', 'slug' => 'peminjaman-delete', 'module' => 'peminjaman', 'action' => 'delete', 'description' => 'Hapus data peminjaman'],
            ['name' => 'peminjaman.approve', 'slug' => 'peminjaman-approve', 'module' => 'peminjaman', 'action' => 'approve', 'description' => 'Setujui peminjaman/pengembalian'],
            
            // USER MODULE
            ['name' => 'user.create', 'slug' => 'user-create', 'module' => 'user', 'action' => 'create', 'description' => 'Tambah data user'],
            ['name' => 'user.read', 'slug' => 'user-read', 'module' => 'user', 'action' => 'read', 'description' => 'Lihat data user'],
            ['name' => 'user.update', 'slug' => 'user-update', 'module' => 'user', 'action' => 'update', 'description' => 'Edit data user'],
            ['name' => 'user.delete', 'slug' => 'user-delete', 'module' => 'user', 'action' => 'delete', 'description' => 'Hapus data user'],
            
            // PERMISSION MODULE (Hanya Super Admin)
            ['name' => 'permission.manage', 'slug' => 'permission-manage', 'module' => 'permission', 'action' => 'manage', 'description' => 'Kelola hak akses'],
            
            // LOG MODULE (Hanya Super Admin)
            ['name' => 'log.read', 'slug' => 'log-read', 'module' => 'log', 'action' => 'read', 'description' => 'Lihat log aktivitas'],
            
            // LAPORAN MODULE
            ['name' => 'laporan.read', 'slug' => 'laporan-read', 'module' => 'laporan', 'action' => 'read', 'description' => 'Lihat laporan'],
            ['name' => 'laporan.export', 'slug' => 'laporan-export', 'module' => 'laporan', 'action' => 'export', 'description' => 'Export laporan'],
        ];

        // Insert permissions safely
        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['slug' => $permission['slug']], // Key to check
                [
                    'name' => $permission['name'],
                    'module' => $permission['module'],
                    'action' => $permission['action'],
                    'description' => $permission['description'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        // Helper function to assign permission if not exists
        $assignPermission = function($levelId, $permissionId) {
            $exists = DB::table('role_permissions')
                ->where('level_id', $levelId)
                ->where('permission_id', $permissionId)
                ->exists();
            
            if (!$exists) {
                DB::table('role_permissions')->insert([
                    'level_id' => $levelId,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        };

        $syncPermissionsByNames = function (int $levelId, array $permissionNames) {
            $permissionIds = [];
            if (!empty($permissionNames)) {
                $permissionIds = DB::table('permissions')
                    ->whereIn('name', $permissionNames)
                    ->pluck('id')
                    ->toArray();
            }

            DB::table('role_permissions')->where('level_id', $levelId)->delete();

            foreach ($permissionIds as $permissionId) {
                DB::table('role_permissions')->insert([
                    'level_id' => $levelId,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        };

        // Assign permissions ke level-level yang ada
        // Super Admin mendapat semua permissions
        $superAdminLevel = DB::table('levels')->whereRaw('LOWER(nama_level) = ?', ['super admin'])->first();
        if ($superAdminLevel) {
            $allPermissions = DB::table('permissions')->pluck('id');
            foreach ($allPermissions as $permissionId) {
                $assignPermission($superAdminLevel->id, $permissionId);
            }
        }

        // Admin mendapat sebagian besar permissions (kecuali manage permission)
        $adminLevel = DB::table('levels')->whereRaw('LOWER(nama_level) = ?', ['admin'])->first();
        if ($adminLevel) {
            $adminPermissions = DB::table('permissions')
                ->where('module', '!=', 'permission')
                ->pluck('id');
            foreach ($adminPermissions as $permissionId) {
                $assignPermission($adminLevel->id, $permissionId);
            }
        }

        // Petugas mendapat permissions untuk buku dan peminjaman
        $petugasLevel = DB::table('levels')
            ->whereRaw('LOWER(nama_level) = ?', ['petugas'])
            ->orWhereRaw('LOWER(nama_level) = ?', ['penjaga'])
            ->first();
        if ($petugasLevel) {
            $petugasPermissions = DB::table('permissions')
                ->whereIn('module', ['buku', 'buku-masuk', 'peminjaman'])
                ->pluck('id');
            foreach ($petugasPermissions as $permissionId) {
                $assignPermission($petugasLevel->id, $permissionId);
            }
        }

        // Peminjam tidak memiliki akses data master/admin.
        // Peminjam hanya mengakses halaman riwayat member yang tidak memakai permission middleware.
        $peminjamLevel = DB::table('levels')->whereRaw('LOWER(nama_level) = ?', ['peminjam'])->first();
        if ($peminjamLevel) {
            $syncPermissionsByNames((int) $peminjamLevel->id, []);
        }

        // Manager/Pemilik fokus ke laporan
        $managerLevel = DB::table('levels')
            ->whereRaw('LOWER(nama_level) = ?', ['manager'])
            ->orWhereRaw('LOWER(nama_level) = ?', ['pemilik'])
            ->first();
        if ($managerLevel) {
            $syncPermissionsByNames((int) $managerLevel->id, ['laporan.read', 'laporan.export']);
        }
    }
}
