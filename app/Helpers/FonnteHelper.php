<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteHelper
{
    /**
     * Kirim pesan WhatsApp via Fonnte API
     *
     * @param string $target Nomor tujuan (format: 08xx / 628xx)
     * @param string $message Isi pesan
     * @return mixed Response dari Fonnte atau false jika gagal
     */
    public static function sendWhatsApp($target, $message)
    {
        $token = env('FONNTE_TOKEN');

        if (empty($token)) {
            Log::error('Fonnte Token belum diset di .env');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Default kode negara Indonesia
            ]);

            $result = $response->json();
            
            // Log response untuk debugging
            Log::info('Fonnte Response:', ['target' => $target, 'response' => $result]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Fonnte Error: ' . $e->getMessage());
            return false;
        }
    }
}
