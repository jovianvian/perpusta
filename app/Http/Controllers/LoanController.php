<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoanController extends Controller
{
    // ------------------- PEMINJAMAN (ADMIN/PETUGAS) -------------------
    public function peminjaman()
    {
        if (session('id') > 0) {
            $data = DB::table('peminjaman_buku')
            ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
            ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
            ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
            ->select(
                'peminjaman_buku.id',
                'books.judul as judul_buku',
                'books.barcode',
                'books.nomor_buku',
                'users.name as nama_peminjam',
                'peminjaman_buku.tanggal_pinjam',
                'peminjaman_buku.tanggal_kembali',
                'peminjaman_buku.status',
                'peminjaman_buku.transaction_type',
                'peminjaman_buku.book_id',
                'peminjaman_buku.user_id'
            )
            ->whereNull('peminjaman_buku.deleted_at')
            ->orderBy('peminjaman_buku.id', 'desc')
            ->get();

            $books = DB::table('books')->select('id', 'judul', 'barcode', 'nomor_buku')->whereNull('deleted_at')->get();
            $users = DB::table('users')->select('id', 'name', 'email')->whereNull('deleted_at')->get();

            return view('datapeminjamanbuku', compact('data', 'books', 'users'));
        } else {
            return view('404');
        }
    }

    public function tambahPeminjaman()
    {
        if (session('id') > 0) {
            $books = DB::table('books')->whereNull('deleted_at')->get();
            $users = DB::table('users')->whereNull('deleted_at')->get();
            $data = null;

            return view('form_peminjaman', compact('books', 'users', 'data'));
        } else {
            return view('404');
        }
    }

    public function simpanPeminjaman(Request $request)
    {
        if (session('id') > 0) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'transaction_type' => 'nullable|in:pinjam,baca_di_tempat',
                'book_id' => 'nullable|exists:books,id',
                'barcode_input' => 'nullable|string|max:64',
            ]);

            $bookId = $request->book_id;
            $barcode = trim((string) $request->barcode_input);

            if (!$bookId && $barcode !== '') {
                $bookId = DB::table('books')
                    ->where(function ($q) use ($barcode) {
                        $q->whereRaw('LOWER(TRIM(barcode)) = ?', [strtolower($barcode)])
                          ->orWhereRaw('LOWER(TRIM(nomor_buku)) = ?', [strtolower($barcode)]);
                    })
                    ->value('id');
            }

            if (!$bookId) {
                return redirect('/peminjaman')->with('error', 'Buku tidak ditemukan. Pilih buku atau scan barcode yang valid.');
            }

            $book = DB::table('books')->where('id', $bookId)->first();
            if (!$book) {
                return redirect('/peminjaman')->with('error', 'Data buku tidak ditemukan.');
            }

            $transactionType = $request->transaction_type ?: 'pinjam';
            $status = $transactionType === 'baca_di_tempat' ? 'baca_di_tempat' : 'dipinjam';
            $tanggalKembali = $transactionType === 'baca_di_tempat' ? now() : now()->addWeek();

            if ($transactionType === 'pinjam' && (int) $book->stok <= 0) {
                return redirect('/peminjaman')->with('error', 'Stok buku habis, tidak bisa dipinjam.');
            }

            DB::table('peminjaman_buku')->insert([
                'book_id' => $bookId,
                'user_id' => $request->user_id,
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => $tanggalKembali,
                'status' => $status,
                'transaction_type' => $transactionType,
                'barcode_scanned' => $barcode !== '' ? $barcode : ($book->barcode ?? null),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($transactionType === 'pinjam') {
                DB::table('books')->where('id', $bookId)->decrement('stok', 1);
            }

            return redirect('/peminjaman')->with('success', $transactionType === 'baca_di_tempat' ? 'Kunjungan baca di tempat berhasil dicatat.' : 'Peminjaman ditambahkan!');
        } else {
            return view('404');
        }
    }

    public function scanKembalikan(Request $request)
    {
        if (!session('id')) {
            return view('404');
        }

        $request->validate([
            'barcode' => 'required|string|max:64',
        ]);

        $barcode = trim((string) $request->barcode);
        $book = DB::table('books')
            ->where(function ($q) use ($barcode) {
                $q->whereRaw('LOWER(TRIM(barcode)) = ?', [strtolower($barcode)])
                  ->orWhereRaw('LOWER(TRIM(nomor_buku)) = ?', [strtolower($barcode)]);
            })
            ->first();
        if (!$book) {
            return redirect('/peminjaman')->with('error', 'Barcode tidak ditemukan.');
        }

        $activeLoan = DB::table('peminjaman_buku')
            ->where('book_id', $book->id)
            ->where('status', 'dipinjam')
            ->whereNull('deleted_at')
            ->orderByDesc('id')
            ->first();

        if (!$activeLoan) {
            return redirect('/peminjaman')->with('error', 'Tidak ada transaksi pinjam aktif untuk barcode ini.');
        }

        DB::table('peminjaman_buku')
            ->where('id', $activeLoan->id)
            ->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
                'updated_at' => now(),
            ]);

        DB::table('books')->where('id', $book->id)->increment('stok', 1);

        return redirect('/peminjaman')->with('success', 'Pengembalian via scan barcode berhasil.');
    }

    public function editPeminjaman($id)
    {
        if (session('id') > 0) {
            $data = DB::table('peminjaman_buku')->where('id', $id)->first();
            $books = DB::table('books')->whereNull('deleted_at')->get();
            $users = DB::table('users')->whereNull('deleted_at')->get();

            return view('form_peminjaman', compact('data', 'books', 'users'));
        } else {
            return view('404');
        }
    }

    public function updatePeminjaman(Request $request)
    {
        if (session('id') > 0) {
            $before = DB::table('peminjaman_buku')->where('id', $request->id)->first();

            $updateData = [
                'book_id' => $request->book_id,
                'user_id' => $request->user_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => $request->status,
                'transaction_type' => $request->transaction_type ?: 'pinjam',
            ];

            DB::table('peminjaman_buku')->where('id', $request->id)->update($updateData);

            $oldValues = $before ? [
                'book_id' => $before->book_id,
                'user_id' => $before->user_id,
                'tanggal_pinjam' => $before->tanggal_pinjam,
                'tanggal_kembali' => $before->tanggal_kembali,
                'status' => $before->status,
            ] : null;

            $perubahanText = 'Data peminjaman diperbarui';
            if ($oldValues) {
                $detailChanges = [];
                foreach ($updateData as $key => $newVal) {
                    if (!array_key_exists($key, $oldValues)) continue;
                    $oldVal = $oldValues[$key];
                    if ((string)$oldVal === (string)$newVal) continue;

                    if ($key === 'book_id') {
                        $label = 'Judul Buku';
                        $oldVal = $oldVal ? DB::table('books')->where('id', $oldVal)->value('judul') : null;
                        $newVal = $newVal ? DB::table('books')->where('id', $newVal)->value('judul') : null;
                    } elseif ($key === 'user_id') {
                        $label = 'Peminjam';
                        $oldVal = $oldVal ? DB::table('users')->where('id', $oldVal)->value('name') : null;
                        $newVal = $newVal ? DB::table('users')->where('id', $newVal)->value('name') : null;
                    } else {
                        $label = ucfirst(str_replace('_', ' ', $key));
                    }

                    $oldText = $oldVal === null || $oldVal === '' ? '-' : (string)$oldVal;
                    $newText = $newVal === null || $newVal === '' ? '-' : (string)$newVal;
                    $detailChanges[] = $label . ': ' . $oldText . ' → ' . $newText;
                }
                if (!empty($detailChanges)) $perubahanText = implode('; ', $detailChanges);
            }

            DB::table('edit_histories')->insert([
                'table_name' => 'peminjaman_buku',
                'row_id' => $request->id,
                'action_type' => 'update',
                'perubahan' => $perubahanText,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => json_encode($updateData),
                'edited_by' => session('id'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect('/peminjaman')->with('success', 'Data diperbarui!');
        } else {
            return view('404');
        }
    }

    public function hapusPeminjaman($id)
    {
        if (session('id') > 0) {
            DB::table('peminjaman_buku')->where('id', $id)->update([
                'deleted_at' => now(),
                'deleted_by' => session('id')
            ]);
            return redirect('/peminjaman')->with('success', 'Data dihapus (Soft Delete)!');
        } else {
            return view('404');
        }
    }

    public function kembalikanPeminjaman($id)
    {
        if (session('id') > 0) {
            $pinjam = DB::table('peminjaman_buku')->where('id', $id)->first();

            DB::table('peminjaman_buku')->where('id', $id)->update([
                'tanggal_kembali' => now(),
                'status' => 'dikembalikan'
            ]);

            if ($pinjam) {
                DB::table('books')->where('id', $pinjam->book_id)->increment('stok', 1);
            }

            return redirect('/peminjaman')->with('success', 'Buku dikembalikan!');
        } else {
            return view('404');
        }
    }

    // ------------------- RIWAYAT (ANGGOTA) -------------------
    public function riwayatPeminjaman()
    {
        if (!session('id')) return view('404');

        $riwayat = DB::table('peminjaman_buku')
        ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
        ->select(
            'peminjaman_buku.id',
            'books.judul',
            'peminjaman_buku.tanggal_pinjam',
            'peminjaman_buku.tanggal_kembali',
            'peminjaman_buku.status'
        )
        ->where('peminjaman_buku.user_id', session('id'))
        ->whereNull('peminjaman_buku.deleted_at')
        ->orderBy('peminjaman_buku.id', 'desc')
        ->get();

        return view('riwayatpeminjaman', compact('riwayat'));
    }

    // ------------------- PROSES USER (ANGGOTA) -------------------
    public function pinjamBuku($id) {
        if (!session('id')) return redirect('/login');
        
        // 1. Cek jumlah buku yang sedang dipinjam (Maksimal 3)
        $activeLoansCount = DB::table('peminjaman_buku')
            ->where('user_id', session('id'))
            ->whereIn('status', ['pending_pinjam', 'dipinjam', 'pending_kembali'])
            ->whereNull('deleted_at')
            ->count();

        if ($activeLoansCount >= 3) {
            return redirect('/riwayat')->with('error', 'Anda hanya boleh meminjam maksimal 3 buku dalam satu waktu.');
        }

        // 2. Cek apakah user sudah meminjam buku dengan judul yang sama
        $bookToBorrow = DB::table('books')->where('id', $id)->first();
        
        if ($bookToBorrow) {
            $duplicateBook = DB::table('peminjaman_buku')
                ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
                ->where('peminjaman_buku.user_id', session('id'))
                ->whereIn('peminjaman_buku.status', ['pending_pinjam', 'dipinjam', 'pending_kembali'])
                ->where('books.judul', $bookToBorrow->judul)
                ->whereNull('peminjaman_buku.deleted_at')
                ->exists();

            if ($duplicateBook) {
                return redirect('/riwayat')->with('error', 'Anda tidak boleh meminjam buku dengan judul yang sama (' . $bookToBorrow->judul . ').');
            }
        }

        // Status awal adalah pending_pinjam (Menunggu persetujuan petugas)
        DB::table('peminjaman_buku')->insert([
            'book_id' => $id,
            'user_id' => session('id'),
            'tanggal_pinjam' => now(),
            'status' => 'pending_pinjam', 
            'created_at' => now()
        ]);
        return redirect('/riwayat')->with('success', 'Permintaan terkirim. Silahkan ke petugas untuk ambil buku.');
    }

    public function ajukanKembali($id) {
        DB::table('peminjaman_buku')->where('id', $id)->update(['status' => 'pending_kembali']);
        return redirect('/riwayat')->with('success', 'Laporan kembali terkirim. Silahkan serahkan buku ke petugas.');
    }

    // ------------------- APPROVAL (PETUGAS) -------------------
    public function konfirmasiPeminjaman($id, $aksi) {
        if (session('level') > 2 && session('level') != 5 && session('level') != 6) return abort(403); 

        $pinjam = DB::table('peminjaman_buku')
            ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
            ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
            ->select('peminjaman_buku.*', 'books.judul', 'users.name', 'users.whatsapp')
            ->where('peminjaman_buku.id', $id)
            ->first();

        if ($aksi == 'setujui_pinjam') {
            DB::table('peminjaman_buku')->where('id', $id)->update([
                'status' => 'dipinjam',
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => now()->addWeek()
            ]);
            
            DB::table('books')->where('id', $pinjam->book_id)->decrement('stok', 1);

            // Kirim WA Notifikasi
            if ($pinjam->whatsapp) {
                $msg = "Halo {$pinjam->name}, Peminjaman buku '{$pinjam->judul}' telah DISETUJUI. Silakan ambil buku di perpustakaan. Harap kembalikan sebelum " . now()->addWeek()->format('d-m-Y');
                \App\Helpers\FonnteHelper::sendWhatsApp($pinjam->whatsapp, $msg);
            }
        } 
        elseif ($aksi == 'setujui_kembali') {
            // Ambil Inputan Kondisi & Denda Tambahan dari Request
            $kondisi = request('kondisi', 'baik'); // baik, rusak, hilang
            $dendaTambahan = request('denda_tambahan', 0);

            // Hitung Denda Keterlambatan
            $tglJatuhTempo = \Carbon\Carbon::parse($pinjam->tanggal_kembali)->startOfDay(); 
            $tglSekarang = now()->startOfDay();
            $dendaTerlambat = 0;

            if ($tglSekarang->greaterThan($tglJatuhTempo)) {
                $selisihHari = $tglSekarang->diffInDays($tglJatuhTempo);
                $dendaTerlambat = $selisihHari * 1000; 
            }

            $totalDenda = $dendaTerlambat + $dendaTambahan;

            // Tentukan Status Akhir
            $statusAkhir = ($kondisi == 'baik') ? 'dikembalikan' : $kondisi;

            DB::table('peminjaman_buku')->where('id', $id)->update([
                'status' => $statusAkhir,
                'tanggal_kembali' => now(),
                'denda' => $totalDenda
            ]);

            // Update Stok Buku
            // Jika Hilang, stok JANGAN ditambah.
            // Jika Baik/Rusak, stok ditambah (fisik buku kembali).
            if ($kondisi != 'hilang') {
                DB::table('books')->where('id', $pinjam->book_id)->increment('stok', 1);
            }

            // Kirim WA Notifikasi Pengembalian
            if ($pinjam->whatsapp) {
                $msg = "Halo {$pinjam->name}, Proses pengembalian buku '{$pinjam->judul}' selesai.";
                $msg .= "\nStatus: " . ucfirst($kondisi);
                
                if ($totalDenda > 0) {
                    $msg .= "\nTotal DENDA: Rp " . number_format($totalDenda, 0, ',', '.');
                    if ($dendaTerlambat > 0) $msg .= " (Keterlambatan)";
                    if ($dendaTambahan > 0) $msg .= " (Kerusakan/Hilang)";
                } else {
                    $msg .= "\nTerima kasih telah mengembalikan tepat waktu dan dalam kondisi baik.";
                }
                \App\Helpers\FonnteHelper::sendWhatsApp($pinjam->whatsapp, $msg);
            }
        }

        return back()->with('success', 'Status berhasil diperbarui!');
    }
}
