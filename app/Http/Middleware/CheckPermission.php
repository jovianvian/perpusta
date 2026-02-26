<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission  Permission yang diperlukan (contoh: buku.create)
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Super Admin selalu punya akses
        $user = DB::table('users')
            ->join('levels', 'users.level_id', '=', 'levels.id')
            ->where('users.id', $userId)
            ->select('users.*', 'levels.nama_level')
            ->first();

        if (!$user) {
            return redirect('/login')->with('error', 'User tidak ditemukan.');
        }

        // Super Admin bypass semua permission check
        if ($user->nama_level === 'Super Admin') {
            return $next($request);
        }

        // Cek apakah user punya permission
        $hasPermission = DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.level_id', $user->level_id)
            ->where('permissions.name', $permission)
            ->exists();

        if (!$hasPermission) {
            return redirect('/home')->with('error', 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }

        return $next($request);
    }
}
