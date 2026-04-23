<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NotificationHelper
{
    public static function notifyUser(int $userId, string $title, string $message, ?string $url = null, string $type = 'info', ?int $createdBy = null): void
    {
        if (!Schema::hasTable('notifications')) {
            return;
        }

        DB::table('notifications')->insert([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'url' => $url,
            'is_read' => false,
            'created_by' => $createdBy,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function notifyUsers(array $userIds, string $title, string $message, ?string $url = null, string $type = 'info', ?int $createdBy = null): void
    {
        if (!Schema::hasTable('notifications')) {
            return;
        }

        $userIds = array_values(array_unique(array_filter($userIds)));
        if (empty($userIds)) {
            return;
        }

        $rows = [];
        $now = now();
        foreach ($userIds as $userId) {
            $rows[] = [
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'url' => $url,
                'is_read' => false,
                'created_by' => $createdBy,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('notifications')->insert($rows);
    }

    public static function notifyAdminsAndSupers(string $title, string $message, ?string $url = null, string $type = 'warning', ?int $createdBy = null): void
    {
        $targetIds = DB::table('users')
            ->join('levels', 'users.level_id', '=', 'levels.id')
            ->whereNull('users.deleted_at')
            ->whereIn(DB::raw('LOWER(levels.nama_level)'), ['admin', 'super admin'])
            ->pluck('users.id')
            ->toArray();

        self::notifyUsers($targetIds, $title, $message, $url, $type, $createdBy);
    }

    public static function notifyBorrowers(string $title, string $message, ?string $url = null, string $type = 'info', ?int $createdBy = null): void
    {
        $targetIds = DB::table('users')
            ->join('levels', 'users.level_id', '=', 'levels.id')
            ->whereNull('users.deleted_at')
            ->whereIn(DB::raw('LOWER(levels.nama_level)'), ['peminjam', 'member', 'anggota'])
            ->pluck('users.id')
            ->toArray();

        self::notifyUsers($targetIds, $title, $message, $url, $type, $createdBy);
    }
}
