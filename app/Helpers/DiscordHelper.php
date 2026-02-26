<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordHelper
{
    public static function sendNotification($message, $title = 'Notification', $color = 3447003)
    {
        $webhookUrl = env('DISCORD_WEBHOOK_URL');

        if (!$webhookUrl) {
            Log::warning('Discord Webhook URL not configured.');
            return;
        }

        try {
            $response = Http::post($webhookUrl, [
                'embeds' => [
                    [
                        'title' => $title,
                        'description' => $message,
                        'color' => $color, // Decimal color (3447003 is Blue)
                        'timestamp' => now()->toIso8601String(),
                        'footer' => [
                            'text' => 'Perpustakaan System'
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Discord Notification Failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Discord Notification Error: ' . $e->getMessage());
        }
    }
}
