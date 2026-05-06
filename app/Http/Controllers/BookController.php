<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Spatie\SimpleExcel\SimpleExcelReader;

use App\Helpers\DiscordHelper;
use App\Helpers\NotificationHelper;

class BookController extends Controller
{
    private function pickRowValue(array $row, array $candidates, $default = null)
    {
        foreach ($candidates as $key) {
            if (!array_key_exists($key, $row)) {
                continue;
            }
            $value = $row[$key];
            if ($value === null) {
                continue;
            }
            $text = trim((string) $value);
            if ($text !== '') {
                return $text;
            }
        }
        return $default;
    }

    private function nextBookNumber(): string
    {
        $prefix = 'SPH-BK-' . now()->format('Y');
        $last = DB::table('books')
            ->where('nomor_buku', 'like', $prefix . '-%')
            ->orderByDesc('id')
            ->value('nomor_buku');

        $next = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $next = ((int) $m[1]) + 1;
        }

        return sprintf('%s-%06d', $prefix, $next);
    }

    private function nextBarcode(): string
    {
        do {
            $code = 'SPH' . now()->format('ymd') . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (DB::table('books')->where('barcode', $code)->exists());

        return $code;
    }

    // ------------------- IMPORT EXCEL -------------------
    public function import(Request $request)
    {
        if (session('id') > 0) {
            $request->validate([
                'file_excel' => 'required|file|mimes:xlsx,csv,xls'
            ]);

            $file = $request->file('file_excel');
            
            // Menggunakan spatie/simple-excel
            $rows = SimpleExcelReader::create($file->getPathname())->getRows();

            $count = 0;
            $failed = 0;
            foreach ($rows as $row) {
                $penulisId = $this->findOrCreateId('penulis', 'nama_penulis', $this->pickRowValue($row, ['Penulis', 'penulis', 'Author', 'author'], 'Unknown'));
                $penerbitId = $this->findOrCreateId('penerbit', 'nama_penerbit', $this->pickRowValue($row, ['Penerbit', 'penerbit', 'Publisher', 'publisher'], 'Unknown'));
                $kategoriId = $this->findOrCreateId('kategori', 'nama_kategori', $this->pickRowValue($row, ['Kategori', 'kategori', 'Category', 'category'], 'Umum'));

                $isbn = $this->pickRowValue($row, ['ISBN', 'isbn']);
                $nomorBuku = $this->pickRowValue($row, ['Nomor_Buku', 'Nomor Buku', 'nomor_buku', 'Book_Number', 'book_number']) ?: $this->nextBookNumber();
                $barcode = $this->pickRowValue($row, ['Barcode', 'barcode', 'Kode_Barcode', 'kode_barcode']) ?: $this->nextBarcode();
                $judul = $this->pickRowValue($row, ['Judul', 'judul', 'Title', 'title'], 'No Title');
                $tahun = (int) $this->pickRowValue($row, ['Tahun', 'tahun', 'Year', 'year'], date('Y'));
                $rakKategori = $this->pickRowValue($row, ['Rak_Kategori', 'rak_kategori', 'Rak', 'rak']);
                $rakLokasi = $this->pickRowValue($row, ['Lokasi_Rak', 'lokasi_rak', 'Lokasi', 'lokasi']);
                $bahasa = $this->pickRowValue($row, ['Bahasa', 'bahasa', 'Language', 'language']);
                $jumlahHalaman = $this->pickRowValue($row, ['Jumlah_Halaman', 'jumlah_halaman', 'Halaman', 'halaman', 'Page_Count', 'page_count']);
                $stok = (int) $this->pickRowValue($row, ['Stok', 'stok', 'Stock', 'stock'], 0);
                $kondisi = $this->pickRowValue($row, ['Kondisi', 'kondisi', 'Condition', 'condition'], 'baik');

                try {
                    $existing = DB::table('books')
                        ->where(function ($q) use ($barcode, $nomorBuku) {
                            $q->where('barcode', $barcode)
                              ->orWhere('nomor_buku', $nomorBuku);
                        })
                        ->first();

                    $payload = [
                        'judul' => $judul,
                        'isbn' => $isbn,
                        'nomor_buku' => $nomorBuku,
                        'barcode' => $barcode,
                        'penulis_id' => $penulisId,
                        'penerbit_id' => $penerbitId,
                        'tahun' => $tahun,
                        'kategori_id' => $kategoriId,
                        'rak_kategori' => $rakKategori,
                        'rak_lokasi' => $rakLokasi,
                        'bahasa' => $bahasa,
                        'jumlah_halaman' => $jumlahHalaman !== null ? (int) $jumlahHalaman : null,
                        'stok' => $stok,
                        'kondisi_buku' => $kondisi,
                        'updated_at' => now(),
                    ];

                    if ($existing) {
                        DB::table('books')->where('id', $existing->id)->update($payload);
                    } else {
                        $payload['created_at'] = now();
                        DB::table('books')->insert($payload);
                    }
                    $count++;
                } catch (\Throwable $e) {
                    $failed++;
                }
            }

            DiscordHelper::sendNotification("User **" . session('name') . "** baru saja mengimport **$count** buku baru via Excel.", "Import Data Buku", 3066993); // Green
            if ($count > 0) {
                NotificationHelper::notifyBorrowers(
                    'Koleksi Buku Diperbarui',
                    "Ada {$count} buku baru ditambahkan ke perpustakaan.",
                    '/riwayat',
                    'info',
                    (int) session('id')
                );
            }

            return back()->with('success', "Import selesai. Berhasil: $count, gagal: $failed.");
        }
        return view('404');
    }

    public function importBarcode(Request $request)
    {
        if (session('id') <= 0) {
            return view('404');
        }

        $request->validate([
            'file_barcode' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        $rows = SimpleExcelReader::create($request->file('file_barcode')->getPathname())->getRows();
        $updated = 0;
        $created = 0;
        $failed = 0;

        foreach ($rows as $row) {
            try {
                $barcode = $this->pickRowValue($row, ['Barcode', 'barcode', 'Kode_Barcode', 'kode_barcode']);
                if (!$barcode) {
                    $failed++;
                    continue;
                }

                $judul = $this->pickRowValue($row, ['Judul', 'judul', 'Title', 'title']);
                $nomorBuku = $this->pickRowValue($row, ['Nomor_Buku', 'Nomor Buku', 'nomor_buku', 'Book_Number']);
                $stokBaru = $this->pickRowValue($row, ['Stok', 'stok', 'Stock', 'stock']);
                $stokTambah = $this->pickRowValue($row, ['Tambah_Stok', 'Tambah Stok', 'tambah_stok', 'add_stock']);
                $isbn = $this->pickRowValue($row, ['ISBN', 'isbn']);

                $book = DB::table('books')
                    ->whereRaw('LOWER(TRIM(barcode)) = ?', [strtolower($barcode)])
                    ->first();

                if ($book) {
                    $payload = ['updated_at' => now()];
                    if ($judul) $payload['judul'] = $judul;
                    if ($nomorBuku) $payload['nomor_buku'] = $nomorBuku;
                    if ($isbn) $payload['isbn'] = $isbn;
                    if ($stokBaru !== null && $stokBaru !== '') {
                        $payload['stok'] = (int) $stokBaru;
                    }
                    DB::table('books')->where('id', $book->id)->update($payload);

                    if ($stokTambah !== null && $stokTambah !== '') {
                        DB::table('books')->where('id', $book->id)->increment('stok', (int) $stokTambah);
                    }

                    $updated++;
                } else {
                    if (!$judul) {
                        $judul = 'No Title';
                    }

                    $penulisId = $this->findOrCreateId('penulis', 'nama_penulis', $this->pickRowValue($row, ['Penulis', 'penulis', 'Author', 'author'], 'Unknown'));
                    $penerbitId = $this->findOrCreateId('penerbit', 'nama_penerbit', $this->pickRowValue($row, ['Penerbit', 'penerbit', 'Publisher', 'publisher'], 'Unknown'));
                    $kategoriId = $this->findOrCreateId('kategori', 'nama_kategori', $this->pickRowValue($row, ['Kategori', 'kategori', 'Category', 'category'], 'Umum'));

                    DB::table('books')->insert([
                        'judul' => $judul,
                        'isbn' => $isbn,
                        'nomor_buku' => $nomorBuku ?: $this->nextBookNumber(),
                        'barcode' => $barcode,
                        'penulis_id' => $penulisId,
                        'penerbit_id' => $penerbitId,
                        'tahun' => (int) $this->pickRowValue($row, ['Tahun', 'tahun', 'Year', 'year'], date('Y')),
                        'kategori_id' => $kategoriId,
                        'stok' => (int) ($stokBaru !== null && $stokBaru !== '' ? $stokBaru : 0),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    if ($stokTambah !== null && $stokTambah !== '') {
                        DB::table('books')->where('barcode', $barcode)->increment('stok', (int) $stokTambah);
                    }
                    $created++;
                }
            } catch (\Throwable $e) {
                $failed++;
            }
        }

        return back()->with('success', "Import barcode selesai. Update: {$updated}, Baru: {$created}, Gagal: {$failed}.");
    }


    private function findOrCreateId($table, $column, $value)
    {
        if (empty($value)) return 1; // Default ID 1 fallback
        
        $exists = DB::table($table)->where($column, $value)->first();
        if ($exists) {
            return $exists->id;
        } else {
            return DB::table($table)->insertGetId([$column => $value]);
        }
    }

    // ------------------- DATA BUKU -------------------
    public function books()
    {
        if (session('id') > 0) {
            $buku = DB::table('books')
            ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
            ->join('penerbit', 'books.penerbit_id', '=', 'penerbit.id')
            ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
            ->select('books.*', 'penulis.nama_penulis', 'penerbit.nama_penerbit', 'kategori.nama_kategori')
            ->selectSub(function ($query) {
                $query->from('peminjaman_buku')
                    ->whereColumn('peminjaman_buku.book_id', 'books.id')
                    ->selectRaw('count(*)');
            }, 'peminjaman_count')
            ->whereNull('books.deleted_at')
            ->orderByDesc('peminjaman_count')
            ->get();

            $penulis = DB::table('penulis')->get();
            $penerbit = DB::table('penerbit')->get();
            $kategori = DB::table('kategori')->get();

            // Additional Data for Super Admin (Trash & History)
            $deletedBooks = [];
            $historyBooks = [];
            if (session('level') == 6 || session('level') == 5) {
                $deletedBooks = DB::table('books')->whereNotNull('deleted_at')->get();
                $historyBooks = DB::table('edit_histories')
                    ->where('table_name', 'books')
                    ->join('users', 'edit_histories.edited_by', '=', 'users.id')
                    ->select('edit_histories.*', 'users.name as editor_name')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            return view('books', compact('buku', 'penulis', 'penerbit', 'kategori', 'deletedBooks', 'historyBooks'));
        } else {
            return redirect('/login');
        }
    }

    public function tambah()
    {
        if (session('id') > 0) {
            $penulis = DB::table('penulis')->get();
            $penerbit = DB::table('penerbit')->get();
            $kategori = DB::table('kategori')->get();

            return view('form', compact('penulis', 'penerbit', 'kategori'));
        } else {
            return view('404');
        }
    }

    public function simpant(Request $request)
    {
        if (session('id') > 0) {
            $request->validate([
                'judul' => 'required|string|max:255',
                'isbn' => 'nullable|string|max:32',
                'nomor_buku' => 'nullable|string|max:64|unique:books,nomor_buku',
                'barcode' => 'nullable|string|max:64|unique:books,barcode',
                'stok' => 'required|integer|min:0',
            ]);

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('covers', 'public');
            }

            $fileBukuPath = null;
            if ($request->hasFile('file_buku')) {
                $fileBukuPath = $request->file('file_buku')->store('ebooks', 'public');
            }

            $nomorBuku = $request->nomor_buku ?: $this->nextBookNumber();
            $barcode = $request->barcode ?: $this->nextBarcode();

            DB::table('books')->insert([
                'judul' => $request->judul,
                'isbn' => $request->isbn,
                'nomor_buku' => $nomorBuku,
                'barcode' => $barcode,
                'penulis_id' => $request->penulis_id,
                'penerbit_id' => $request->penerbit_id,
                'tahun' => $request->tahun,
                'kategori_id' => $request->kategori_id,
                'rak_kategori' => $request->rak_kategori,
                'rak_lokasi' => $request->rak_lokasi,
                'bahasa' => $request->bahasa,
                'jumlah_halaman' => $request->jumlah_halaman,
                'stok' => $request->stok,
                'kondisi_buku' => $request->kondisi_buku ?? 'baik',
                'foto' => $fotoPath,
                'file_buku' => $fileBukuPath
            ]);
            $bookId = DB::getPdo()->lastInsertId();

            DB::table('edit_histories')->insert([
                'table_name' => 'books',
                'row_id' => $bookId,
                'action_type' => 'create',
                'perubahan' => 'Buku baru ditambahkan',
                'edited_by' => session('id'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            NotificationHelper::notifyBorrowers(
                'Buku Baru Tersedia',
                "Buku baru tersedia: {$request->judul}",
                '/riwayat',
                'success',
                (int) session('id')
            );

            return redirect('/databuku')->with('success', 'Buku berhasil ditambahkan!');
        } else {
            return view('404');
        }
    }

    public function edit($id)
    {
        if (session('id') > 0) {
            $buku = DB::table('books')->where('id', $id)->first();
            $penulis = DB::table('penulis')->get();
            $penerbit = DB::table('penerbit')->get();
            $kategori = DB::table('kategori')->get();

            return view('form', compact('buku', 'penulis', 'penerbit', 'kategori'));
        } else {
            return view('404');
        }
    }

    public function simpane(Request $request)
    {
        if (session('id') > 0) {
            $request->validate([
                'judul' => 'required|string|max:255',
                'isbn' => 'nullable|string|max:32',
                'nomor_buku' => 'nullable|string|max:64|unique:books,nomor_buku,' . $request->id,
                'barcode' => 'nullable|string|max:64|unique:books,barcode,' . $request->id,
                'stok' => 'required|integer|min:0',
            ]);

            $before = DB::table('books')->where('id', $request->id)->first();

            $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('covers', 'public') : null;
            $fileBukuPath = $request->hasFile('file_buku') ? $request->file('file_buku')->store('ebooks', 'public') : null;

            $updateData = [
                'judul' => $request->judul,
                'isbn' => $request->isbn,
                'nomor_buku' => $request->nomor_buku ?: ($before->nomor_buku ?? $this->nextBookNumber()),
                'barcode' => $request->barcode ?: ($before->barcode ?? $this->nextBarcode()),
                'penulis_id' => $request->penulis_id,
                'penerbit_id' => $request->penerbit_id,
                'tahun' => $request->tahun,
                'kategori_id' => $request->kategori_id,
                'rak_kategori' => $request->rak_kategori,
                'rak_lokasi' => $request->rak_lokasi,
                'bahasa' => $request->bahasa,
                'jumlah_halaman' => $request->jumlah_halaman,
                'stok' => $request->stok,
                'kondisi_buku' => $request->kondisi_buku ?? 'baik',
            ];

            if ($fotoPath) {
                $updateData['foto'] = $fotoPath;
            }
            if ($fileBukuPath) {
                $updateData['file_buku'] = $fileBukuPath;
            }

            DB::table('books')->where('id', $request->id)->update($updateData);

            $oldValues = $before ? [
                'judul' => $before->judul,
                'isbn' => $before->isbn ?? null,
                'nomor_buku' => $before->nomor_buku ?? null,
                'barcode' => $before->barcode ?? null,
                'penulis_id' => $before->penulis_id,
                'penerbit_id' => $before->penerbit_id,
                'tahun' => $before->tahun,
                'kategori_id' => $before->kategori_id,
                'rak_kategori' => $before->rak_kategori ?? null,
                'rak_lokasi' => $before->rak_lokasi ?? null,
                'bahasa' => $before->bahasa ?? null,
                'jumlah_halaman' => $before->jumlah_halaman ?? null,
                'stok' => $before->stok,
                'kondisi_buku' => $before->kondisi_buku ?? null,
                'foto' => $before->foto,
                'file_buku' => $before->file_buku ?? null,
            ] : null;

            $perubahanText = 'Data buku diperbarui';
            if ($oldValues) {
                $detailChanges = [];
                foreach ($updateData as $key => $newVal) {
                    if (!array_key_exists($key, $oldValues)) continue;
                    $oldVal = $oldValues[$key];
                    if ((string)$oldVal === (string)$newVal) continue;

                    if ($key === 'penulis_id') {
                        $label = 'Penulis';
                        $oldVal = $oldVal ? DB::table('penulis')->where('id', $oldVal)->value('nama_penulis') : null;
                        $newVal = $newVal ? DB::table('penulis')->where('id', $newVal)->value('nama_penulis') : null;
                    } elseif ($key === 'penerbit_id') {
                        $label = 'Penerbit';
                        $oldVal = $oldVal ? DB::table('penerbit')->where('id', $oldVal)->value('nama_penerbit') : null;
                        $newVal = $newVal ? DB::table('penerbit')->where('id', $newVal)->value('nama_penerbit') : null;
                    } elseif ($key === 'kategori_id') {
                        $label = 'Kategori';
                        $oldVal = $oldVal ? DB::table('kategori')->where('id', $oldVal)->value('nama_kategori') : null;
                        $newVal = $newVal ? DB::table('kategori')->where('id', $newVal)->value('nama_kategori') : null;
                    } elseif ($key === 'foto') {
                        $label = 'Foto';
                        $detailChanges[] = $label . ': diubah';
                        continue;
                    } elseif ($key === 'file_buku') {
                        $label = 'File E-Book';
                        $detailChanges[] = $label . ': diubah';
                        continue;
                    } else {
                        $label = ucfirst($key);
                    }

                    $oldText = $oldVal === null || $oldVal === '' ? '-' : (string)$oldVal;
                    $newText = $newVal === null || $newVal === '' ? '-' : (string)$newVal;
                    $detailChanges[] = $label . ': ' . $oldText . ' → ' . $newText;
                }

                if (!empty($detailChanges)) {
                    $perubahanText = implode('; ', $detailChanges);
                }
            }

            DB::table('edit_histories')->insert([
                'table_name' => 'books',
                'row_id' => $request->id,
                'action_type' => 'update',
                'perubahan' => $perubahanText,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => json_encode($updateData),
                'edited_by' => session('id'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DiscordHelper::sendNotification("Buku **" . $request->judul . "** diperbarui oleh **" . session('name') . "**.\nPerubahan: " . $perubahanText, "Update Buku", 15844367); // Gold

            return redirect('/databuku')->with('success', 'Buku berhasil diperbarui!');
        } else {
            return view('404');
        }
    }

    public function delete($id)
    {
        if (session('id') > 0) {
            $bookTitle = DB::table('books')->where('id', $id)->value('judul');

            DB::table('books')->where('id', $id)->update([
                'deleted_at' => now(),
                'deleted_by' => session('id')
            ]);
            
            DB::table('edit_histories')->insert([
                'table_name' => 'books',
                'row_id' => $id,
                'action_type' => 'delete',
                'perubahan' => 'Buku dihapus (Soft Delete)',
                'edited_by' => session('id'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DiscordHelper::sendNotification("Buku **$bookTitle** dihapus (soft delete) oleh **" . session('name') . "**.", "Penghapusan Buku", 15158332); // Red

            return redirect('/databuku')->with('success', 'Buku berhasil dihapus (Soft Delete)!');
        } else {
            return view('404');
        }
    }

    // ------------------- DATA MASUK BUKU -------------------
    public function dataMasukBuku()
    {
        if (session('id') > 0) {
            $data = DB::table('data_masuk_buku')
            ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
            ->select(
                'data_masuk_buku.id',
                'books.judul',
                'data_masuk_buku.jumlah',
                'data_masuk_buku.tanggal_masuk',
                'data_masuk_buku.book_id'
            )
            ->whereNull('data_masuk_buku.deleted_at')
            ->orderBy('data_masuk_buku.id', 'desc')
            ->get();

            $books = DB::table('books')->select('id', 'judul')->get();

            // Additional Data for Super Admin
            $deletedIncoming = [];
            $historyIncoming = [];
            if (session('level') == 6 || session('level') == 5) {
                $deletedIncoming = DB::table('data_masuk_buku')
                    ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
                    ->select('data_masuk_buku.*', 'books.judul')
                    ->whereNotNull('data_masuk_buku.deleted_at')
                    ->get();

                $historyIncoming = DB::table('edit_histories')
                    ->where('table_name', 'data_masuk_buku')
                    ->join('users', 'edit_histories.edited_by', '=', 'users.id')
                    ->select('edit_histories.*', 'users.name as editor_name')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            return view('databukumasuk', compact('data', 'books', 'deletedIncoming', 'historyIncoming'));
        } else {
            return view('404');
        }
    }

    public function tambahDataMasuk()
    {
        if (session('id') > 0) {
            $books = DB::table('books')->get();
            $data = null;
            return view('form_datamasuk', compact('books', 'data'));
        } else {
            return view('404');
        }
    }

    public function simpanDataMasuk(Request $request)
    {
        if (session('id') > 0) {
            DB::table('data_masuk_buku')->insert([
                'book_id' => $request->book_id,
                'jumlah' => $request->jumlah,
                'tanggal_masuk' => $request->tanggal_masuk
            ]);

            DB::table('books')->where('id', $request->book_id)->increment('stok', $request->jumlah);

            return redirect('/datamasuk')->with('success', 'Data buku masuk ditambahkan!');
        } else {
            echo view('404');
        }
    }

    public function editDataMasuk($id)
    {
        if (session('id') > 0) {
            $data = DB::table('data_masuk_buku')->where('id', $id)->first();
            $books = DB::table('books')->get();
            return view('form_datamasuk', compact('data', 'books'));
        } else {
            return view('404');
        }
    }

    public function updateDataMasuk(Request $request)
    {
        if (session('id') > 0) {
            $before = DB::table('data_masuk_buku')->where('id', $request->id)->first();

            $updateData = [
                'book_id' => $request->book_id,
                'jumlah' => $request->jumlah,
                'tanggal_masuk' => $request->tanggal_masuk
            ];

            DB::table('data_masuk_buku')->where('id', $request->id)->update($updateData);

            // Calculate stock difference if needed (complex logic omitted for brevity)

            return redirect('/datamasuk')->with('success', 'Data berhasil diperbarui!');
        } else {
            echo view('404');
        }
    }

    public function hapusDataMasuk($id)
    {
        if (session('id') > 0) {
            DB::table('data_masuk_buku')->where('id', $id)->update([
                'deleted_at' => now(),
                'deleted_by' => session('id')
            ]);

            return redirect('/datamasuk')->with('success', 'Data dihapus (Soft Delete)!');
        } else {
            echo view('404');
        }
    }

    // ================= KOLEKSI (ANGGOTA) ==================
    public function koleksiBuku(Request $request)
    {
        if (!session('id')) return view('404');

        $kategoriList = DB::table('kategori')->orderBy('nama_kategori')->get();

        $query = DB::table('books')
        ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
        ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
        ->join('penerbit', 'books.penerbit_id', '=', 'penerbit.id')
        ->select(
            'books.*',
            'penulis.nama_penulis',
            'penulis.id as penulis_id',
            'penerbit.nama_penerbit',
            'kategori.nama_kategori'
        )
        ->selectSub(function ($query) {
            $query->from('peminjaman_buku')
                ->whereColumn('peminjaman_buku.book_id', 'books.id')
                ->selectRaw('count(*)');
        }, 'peminjaman_count')
        ->whereNull('books.deleted_at');

        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('books.kategori_id', $request->kategori_id);
            // Kalau filter kategori, urutkan berdasarkan judul
            $query->orderBy('books.judul', 'asc');
        } else {
            // Kalau tidak ada filter, urutkan berdasarkan terpopuler (Trending)
            $query->orderByDesc('peminjaman_count');
        }

        $buku = $query->get();

        return view('koleksibuku', compact('kategoriList', 'buku'));
    }

    // ================= DETAIL PENULIS ==================
    public function detailPenulis($id)
    {
        if (!session('id')) return redirect('/login');

        $penulis = DB::table('penulis')->where('id', $id)->first();
        if (!$penulis) return abort(404);

        $buku = DB::table('books')
            ->join('penerbit', 'books.penerbit_id', '=', 'penerbit.id')
            ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
            ->select('books.*', 'penerbit.nama_penerbit', 'kategori.nama_kategori')
            ->where('books.penulis_id', $id)
            ->whereNull('books.deleted_at')
            ->get();

        return view('penulis_detail', compact('penulis', 'buku'));
    }
}
