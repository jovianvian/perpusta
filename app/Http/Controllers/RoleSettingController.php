<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleSettingController extends Controller
{
    // Halaman List Level
    public function index()
    {
        // Security Check: Only Super Admin & Admin
        $userId = session('id');
        if (!$userId) return redirect('/login');
        
        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user || ($user->level_id != 5 && $user->level_id != 6)) {
            return abort(403);
        }

        // Kita exclude Super Admin agar tidak tidak sengaja teredit (Opsional)
        // Atau biarkan saja semua tampil
        $levels = Level::all(); 
        return view('settings.index', compact('levels'));
    }

    // Halaman Edit (Form Centang-centang)
    public function edit($id)
    {
        // Security Check
        $userId = session('id');
        if (!$userId) return redirect('/login');
        
        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user || ($user->level_id != 5 && $user->level_id != 6)) {
            return abort(403);
        }

        $level = Level::with('permissions')->findOrFail($id);
        
        // Ambil permission dan kelompokkan berdasarkan 'module' biar rapi
        $permissions = Permission::all()->groupBy('module');

        return view('settings.edit', compact('level', 'permissions'));
    }

    // Proses Simpan ke Database
    public function update(Request $request, $id)
    {
        // Security Check
        $userId = session('id');
        if (!$userId) return redirect('/login');
        
        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user || ($user->level_id != 5 && $user->level_id != 6)) {
            return abort(403);
        }

        $level = Level::findOrFail($id);

        // Ambil array ID permission yang dicentang
        $permissionIds = $request->input('permissions', []);

        // MAGIC FUNCTION: sync()
        // Otomatis hapus yang lama, masukkan yang baru ke tabel role_permissions
        $level->permissions()->sync($permissionIds);

        return redirect()->route('settings.index')->with('success', 'Hak akses untuk ' . $level->nama_level . ' berhasil diperbarui!');
    }
}