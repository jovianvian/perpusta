<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\VerifyEmail;

class AuthController extends Controller
{
    // ------------------- LOGIN -------------------
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = trim($request->email);
        $password = $request->password;

        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return back()->with('error', 'Email atau password salah')->withInput($request->only('email'));
        }

        // CEK PASSWORD
        if (empty($user->password)) {
            return back()->with('error', 'Akun Anda bermasalah. Silakan hubungi admin.')->withInput($request->only('email'));
        }

        // Cek Hash
        $isHashed = str_starts_with($user->password, '$2y$') || 
                    str_starts_with($user->password, '$2a$') || 
                    str_starts_with($user->password, '$argon2');

        if ($isHashed) {
            if (!Hash::check($password, $user->password)) {
                return back()->with('error', 'Email atau password salah')->withInput($request->only('email'));
            }
        } else {
            // Backward compatibility untuk plain text (Development only)
            if ($password !== $user->password) {
                return back()->with('error', 'Email atau password salah')->withInput($request->only('email'));
            }
            // Auto-update ke hash
            DB::table('users')->where('id', $user->id)->update(['password' => Hash::make($password)]);
        }

        // CEK VERIFIKASI EMAIL
        if (is_null($user->email_verified_at) || empty($user->email_verified_at)) {
            return back()
                ->with('error', 'Akun Anda belum terverifikasi. Silakan verifikasi email terlebih dahulu.')
                ->with('unverified_email', $user->email)
                ->withInput($request->only('email'));
        }

        // LOGIN SUCCESS
        Session::put('id', $user->id);
        Session::put('name', $user->name);
        Session::put('level', $user->level_id);

        // Record Login History
        DB::table('edit_histories')->insert([
            'table_name' => 'users',
            'row_id' => $user->id,
            'action_type' => 'login',
            'edited_by' => $user->id,
            'perubahan' => 'User logged in',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/home');
    }

    public function logout(Request $request)
    {
        $userId = session('id');
        if ($userId) {
            DB::table('edit_histories')->insert([
                'table_name' => 'users',
                'row_id' => $userId,
                'action_type' => 'logout',
                'edited_by' => $userId,
                'perubahan' => 'User logged out',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        Session::flush();
        return redirect('/login');
    }

    // ------------------- REGISTER -------------------
    public function showRegister()
    {
        $operators = ['+', '-', 'x'];
        $op = $operators[array_rand($operators)];

        if ($op === '+') {
            $a = rand(5, 20); $b = rand(5, 20); $result = $a + $b;
        } elseif ($op === '-') {
            $a = rand(15, 40); $b = rand(5, 15);
            if ($b > $a) { $temp = $a; $a = $b; $b = $temp; }
            $result = $a - $b;
        } else {
            $a = rand(2, 10); $b = rand(2, 10); $result = $a * $b;
        }

        session(['register_captcha_result' => $result]);

        return view('register', [
            'captcha_a' => $a,
            'captcha_b' => $b,
            'captcha_op' => $op,
            'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY')
        ]);
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3|confirmed',
        ]);

        // Captcha Logic (Simplified)
        $googleValid = false;
        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
        $recaptchaResponse = $request->input('g-recaptcha-response');

        if ($recaptchaSecret && $recaptchaResponse) {
            try {
                $response = Http::withoutVerifying()->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $recaptchaSecret,
                    'response' => $recaptchaResponse,
                    'remoteip' => $request->ip(),
                ]);
                if ($response->successful() && $response->json('success')) $googleValid = true;
            } catch (\Exception $e) { $googleValid = false; }
        }

        if (!$googleValid) {
            $expected = session('register_captcha_result');
            $answer = $request->input('captcha_answer');
            if ($expected === null || (string)$answer !== (string)$expected) {
                return back()->withErrors(['captcha' => 'Captcha matematika salah.'])->withInput($request->except('password', 'password_confirmation'));
            }
        }

        // Create User
        $verificationToken = Str::random(64);
        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level_id' => 3, // Default Member/Peminjam
            'verification_code' => $verificationToken,
            'code_expires_at' => now()->addHours(24),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('peminjams')->insert([
            'user_id' => $userId,
            'alamat' => $request->alamat ?? null,
            'no_hp' => $request->no_hp ?? null,
            'jenis_kelamin' => $request->jenis_kelamin ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Send Email
        try {
            $verificationUrl = url('/verify-email/' . $verificationToken);
            Mail::to($request->email)->send(new VerifyEmail($verificationUrl, $request->name));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email verifikasi.');
        }

        return redirect()->route('verification.form')->with('email', $request->email)->with('success', 'Email verifikasi telah dikirim.');
    }

    public function verifyEmail($token)
    {
        $user = DB::table('users')->where('verification_code', $token)->where('code_expires_at', '>', now())->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Link verifikasi tidak valid atau kadaluwarsa.');
        }

        DB::table('users')->where('id', $user->id)->update([
            'email_verified_at' => now(),
            'verification_code' => null,
            'code_expires_at' => null,
            'updated_at' => now()
        ]);

        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
    }

    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = DB::table('users')->where('email', $request->email)->whereNull('email_verified_at')->first();

        if (!$user) return back()->with('error', 'Email tidak ditemukan atau sudah terverifikasi.');

        $verificationToken = Str::random(64);
        DB::table('users')->where('id', $user->id)->update([
            'verification_code' => $verificationToken,
            'code_expires_at' => now()->addHours(24),
            'updated_at' => now()
        ]);

        try {
            Mail::to($request->email)->send(new VerifyEmail(url('/verify-email/' . $verificationToken), $user->name));
            return back()->with('success', 'Email verifikasi dikirim ulang.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email.');
        }
    }

    // ------------------- PASSWORD RESET -------------------
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $method = $request->input('method', 'email');

        if ($method === 'whatsapp') {
            $request->validate(['whatsapp' => 'required']);
            
            $user = DB::table('users')
                ->leftJoin('peminjams', 'users.id', '=', 'peminjams.user_id')
                ->leftJoin('penjagas', 'users.id', '=', 'penjagas.user_id')
                ->where('users.no_hp', $request->whatsapp)
                ->orWhere('peminjams.no_hp', $request->whatsapp)
                ->orWhere('penjagas.no_hp', $request->whatsapp)
                ->select('users.*')
                ->first();

            if (!$user) return back()->with('error', 'Nomor WhatsApp tidak ditemukan.');

            $otp = rand(100000, 999999);
            DB::table('password_resets')->where('email', $user->email)->delete();
            DB::table('password_resets')->insert([
                'email' => $user->email,
                'token' => $otp,
                'created_at' => now()
            ]);

            // Kirim WA via Fonnte
            $message = "Halo {$user->name}, Kode OTP reset password Anda adalah: *{$otp}*. Jangan berikan kode ini kepada siapapun.";
            $fonnte = \App\Helpers\FonnteHelper::sendWhatsApp($request->whatsapp, $message);

            if ($fonnte && isset($fonnte['status']) && $fonnte['status']) {
                return redirect()->route('password.otp', ['email' => $user->email])
                    ->with('success', 'Kode OTP berhasil dikirim ke WhatsApp.');
            } else {
                // Fallback jika API gagal (misal token belum diset)
                // Log::error('Fonnte Failed', ['response' => $fonnte]);
                return redirect()->route('password.otp', ['email' => $user->email])
                    ->with('warning', 'Gagal mengirim WA (API Error). Kode OTP (Dev Mode): ' . $otp);
            }

        } else {
            $request->validate(['email' => 'required|email']);
            $user = DB::table('users')->where('email', $request->email)->first();

            if (!$user) return back()->with('error', 'Email tidak ditemukan.');

            $token = Str::random(60);
            DB::table('password_resets')->where('email', $request->email)->delete();
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]);

            try {
                 Mail::send('emails.reset_password', ['token' => $token, 'email' => $request->email], function($message) use($request) {
                     $message->to($request->email);
                     $message->subject('Reset Password Notification');
                 });
                 return back()->with('success', 'Link reset password telah dikirim ke email.');
            } catch (\Exception $e) {
                 return back()->with('error', 'Gagal mengirim email.');
            }
        }
    }

    public function showOtpForm(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
            'password' => 'required|confirmed|min:6'
        ]);

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$record) return back()->with('error', 'Kode OTP salah.');
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->with('error', 'Kode OTP kadaluwarsa.');
        }

        DB::table('users')->where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password berhasil direset.');
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPasswordHandler(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required'
        ]);

        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) return back()->with('error', 'Token invalid.');
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->with('error', 'Token expired.');
        }

        DB::table('users')->where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password berhasil direset.');
    }
}
