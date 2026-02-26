<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RequestBookController extends Controller
{
    // ---------------- MEMBER AREA ----------------

    public function indexMember()
    {
        if (!session('id')) return redirect('/login');

        // Ambil riwayat request user ini
        $myRequests = DB::table('request_buku')
            ->where('user_id', session('id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('request_buku_member', compact('myRequests'));
    }

    public function store(Request $request)
    {
        if (!session('id')) return redirect('/login');

        $request->validate([
            'judul_buku' => 'required|string|max:255',
            'penulis' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        DB::table('request_buku')->insert([
            'user_id' => session('id'),
            'judul_buku' => $request->judul_buku,
            'penulis' => $request->penulis,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Request buku berhasil dikirim! Admin akan meninjaunya.');
    }

    // ---------------- ADMIN AREA ----------------

    public function indexAdmin()
    {
        if (session('level') > 2 && session('level') != 5 && session('level') != 6) return abort(403);

        $requests = DB::table('request_buku')
            ->join('users', 'request_buku.user_id', '=', 'users.id')
            ->select('request_buku.*', 'users.name as pemohon', 'users.whatsapp')
            ->orderBy('request_buku.created_at', 'desc')
            ->get();

        return view('request_buku_admin', compact('requests'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (session('level') > 2 && session('level') != 5 && session('level') != 6) return abort(403);

        $status = $request->input('status'); // disetujui / ditolak
        $alasan = $request->input('alasan_penolakan');

        $reqData = DB::table('request_buku')
            ->join('users', 'request_buku.user_id', '=', 'users.id')
            ->select('request_buku.*', 'users.name', 'users.whatsapp')
            ->where('request_buku.id', $id)
            ->first();

        if (!$reqData) return back()->with('error', 'Data tidak ditemukan');

        DB::table('request_buku')->where('id', $id)->update([
            'status' => $status,
            'alasan_penolakan' => ($status == 'ditolak') ? $alasan : null,
            'updated_at' => now()
        ]);

        // Kirim Notifikasi WA
        if ($reqData->whatsapp) {
            if ($status == 'disetujui') {
                $msg = "Halo {$reqData->name}, Kabar Gembira! Request buku Anda '{$reqData->judul_buku}' telah DISETUJUI oleh Admin. Kami akan segera memproses pengadaan buku tersebut.";
            } else {
                $msg = "Halo {$reqData->name}, Mohon maaf, Request buku Anda '{$reqData->judul_buku}' DITOLAK. Alasan: {$alasan}";
            }
            \App\Helpers\FonnteHelper::sendWhatsApp($reqData->whatsapp, $msg);
        }

        return back()->with('success', 'Status request diperbarui & notifikasi dikirim!');
    }
}
