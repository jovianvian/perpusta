<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationUrl;
    public $userName;

    public function __construct($verificationUrl, $userName)
    {
        $this->verificationUrl = $verificationUrl;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject('Verifikasi Email Akun Anda')
                    ->view('emails.verify-email', [
                        'verificationUrl' => $this->verificationUrl,
                        'userName' => $this->userName
                    ]);
    }
}
