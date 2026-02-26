<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    public $pin; // KITA GANTI NAMA JADI 'pin'

    public function __construct($otpYangDiterima)
    {
        $this->pin = $otpYangDiterima;
    }

    public function build()
    {
        return $this->subject('Kode Verifikasi Akun Kamu')
                    // KITA PAKE CARA PALING KASAR: ARRAY LANGSUNG
        ->view('emails.verification', [
            'pin' => $this->pin
        ]);
    }
}