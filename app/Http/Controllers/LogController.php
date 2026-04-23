<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    // ------------------- LOG ACTIVITY -------------------
    public function activityLog(Request $request)
    {
        $userId = session('id');
        if (!$userId) return redirect('/login');

        $user = DB::table('users')->where('id', $userId)->first();
        $isSuperAdmin = in_array((int) $user->level_id, [5, 6], true); // Super Admin bisa lihat semua log

        $q = $request->q;
        
        $query = DB::table('edit_histories')
            ->leftJoin('users', 'edit_histories.edited_by', '=', 'users.id')
            ->select('edit_histories.*', 'users.name as user_name', 'users.email as user_email');

        // Jika BUKAN Super Admin, filter hanya log miliknya sendiri
        if (!$isSuperAdmin) {
            $query->where('edit_histories.edited_by', $userId);
        }

        $logs = $query->when($q, function($query) use ($q) {
                $query->where('users.name', 'like', "%$q%")
                      ->orWhere('edit_histories.action_type', 'like', "%$q%")
                      ->orWhere('edit_histories.ip_address', 'like', "%$q%")
                      ->orWhere('edit_histories.perubahan', 'like', "%$q%");
            })
            ->orderBy('edit_histories.created_at', 'desc')
            ->paginate(20);
            
        return view('activity_log', compact('logs', 'isSuperAdmin'));
    }

    // ------------------- LOCATION TRACKING -------------------
    public function updateLocation(Request $request)
    {
        $userId = session('id');
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $intervalMinutes = (int) env('LOCATION_PING_INTERVAL_MINUTES', 10);
        $now = now();

        // Throttle location log: update latest ping if still inside interval.
        $recentPing = DB::table('edit_histories')
            ->where('edited_by', $userId)
            ->where('action_type', 'location_ping')
            ->where('created_at', '>=', $now->copy()->subMinutes($intervalMinutes))
            ->orderByDesc('created_at')
            ->first();

        if ($recentPing) {
            DB::table('edit_histories')
                ->where('id', $recentPing->id)
                ->update([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'updated_at' => $now
                ]);
        } else {
            DB::table('edit_histories')->insert([
                'table_name' => 'users',
                'row_id' => $userId,
                'action_type' => 'location_ping',
                'edited_by' => $userId,
                'perubahan' => 'User location update',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        // 2. Update the LATEST 'login' log for this user (if within last 30 mins)
        // This ensures the "LOGIN" row in the table also shows the location
        DB::table('edit_histories')
            ->where('edited_by', $userId)
            ->where('action_type', 'login')
            ->where('created_at', '>=', $now->copy()->subMinutes(30))
            ->orderByDesc('created_at')
            ->limit(1)
            ->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'updated_at' => $now
            ]);

        return response()->json(['status' => 'success']);
    }
}
