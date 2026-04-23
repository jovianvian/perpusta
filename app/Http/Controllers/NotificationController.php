<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function markRead(Request $request, int $id): RedirectResponse
    {
        $userId = session('id');
        if (!$userId) {
            return redirect('/login');
        }
        if (!Schema::hasTable('notifications')) {
            return back();
        }

        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'is_read' => true,
                'read_at' => now(),
                'updated_at' => now(),
            ]);

        $redirect = $request->query('redirect');
        if ($redirect) {
            return redirect($redirect);
        }

        return back();
    }

    public function markAllRead(): RedirectResponse
    {
        $userId = session('id');
        if (!$userId) {
            return redirect('/login');
        }
        if (!Schema::hasTable('notifications')) {
            return back();
        }

        DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
                'updated_at' => now(),
            ]);

        return back()->with('success', __('Semua notifikasi ditandai sudah dibaca.'));
    }

    public function unreadCount(Request $request)
    {
        $userId = session('id');
        if (!$userId || !Schema::hasTable('notifications')) {
            return response()->json(['count' => 0]);
        }

        $count = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
