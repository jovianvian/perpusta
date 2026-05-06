<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Elibyy\TCPDF\Facades\TCPDF;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // ========================= LAPORAN PEMINJAMAN =========================
    public function laporanPeminjaman(Request $request)
    {
        if (session('id') > 0) {
            $query = DB::table('peminjaman_buku')
            ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
            ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
            ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
            ->select(
                'peminjaman_buku.id',
                'users.name as nama_peminjam',
                'kategori.nama_kategori as kategori',
                'books.judul as judul_buku',
                'peminjaman_buku.tanggal_pinjam',
                'peminjaman_buku.tanggal_kembali',
                'peminjaman_buku.status',
                'peminjaman_buku.transaction_type'
            )
            ->orderBy('peminjaman_buku.id', 'desc');

            if ($request->from && $request->to) {
                $query->whereBetween('peminjaman_buku.tanggal_pinjam', [$request->from, $request->to . ' 23:59:59']);
            }

            $laporan = $query->get();

            return view('laporanpeminjaman', [
                'laporan' => $laporan,
                'from' => $request->from,
                'to' => $request->to
            ]);
        } else {
            return view('404');
        }
    }

    public function printPeminjaman(Request $request)
    {
        $query = DB::table('peminjaman_buku')
        ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
        ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
        ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
        ->select(
            'users.name as nama_peminjam', 
            'books.judul as judul_buku',
            'peminjaman_buku.tanggal_pinjam', 
            'peminjaman_buku.tanggal_kembali',
            'peminjaman_buku.status', 
            'peminjaman_buku.transaction_type',
            'kategori.nama_kategori as kategori'
        )
        ->orderBy('peminjaman_buku.id', 'desc');

        if ($request->from && $request->to) {
            $query->whereBetween('peminjaman_buku.tanggal_pinjam', [$request->from, $request->to . ' 23:59:59']);
        }

        $laporan = $query->get();

        return view('print_peminjaman', [
            'laporan' => $laporan,
            'from' => $request->from,
            'to' => $request->to
        ]);
    }

    public function pdfPeminjaman(Request $request)
    {
        $query = DB::table('peminjaman_buku')
        ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
        ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
        ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
        ->select(
            'users.name as nama_peminjam',
            'books.judul as judul_buku',
            'peminjaman_buku.tanggal_pinjam',
            'peminjaman_buku.tanggal_kembali',
            'peminjaman_buku.status',
            'peminjaman_buku.transaction_type',
            'kategori.nama_kategori as kategori'
        )
        ->orderBy('peminjaman_buku.id', 'desc');

        if ($request->from && $request->to) {
            $query->whereBetween('peminjaman_buku.tanggal_pinjam', [$request->from, $request->to . ' 23:59:59']);
        }

        $laporan = $query->get();

        $pdf = new \TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Laporan Peminjaman Buku', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', '', 10);
        $html = '
        <table border="1" cellpadding="5" width="100%">
        <tr style="font-weight:bold; background-color:#f2f2f2;">
        <th width="5%">No</th>
        <th width="18%">Nama Peminjam</th>
        <th width="20%">Judul Buku</th>
        <th width="10%">Kategori</th>
        <th width="12%">Tanggal Pinjam</th>
        <th width="12%">Tanggal Kembali</th>
        <th width="12%">Jenis</th>
        <th width="11%">Status</th>
        </tr>';

        $no = 1;
        foreach ($laporan as $data) {
            $html .= '
            <tr>
            <td>'.$no++.'</td>
            <td>'.$data->nama_peminjam.'</td>
            <td>'.$data->judul_buku.'</td>
            <td>'.$data->kategori.'</td>
            <td>'.$data->tanggal_pinjam.'</td>
            <td>'.$data->tanggal_kembali.'</td>
            <td>'.(($data->transaction_type === 'baca_di_tempat') ? 'Baca di Tempat' : 'Pinjam').'</td>
            <td>'.$data->status.'</td>
            </tr>';
        }

        $html .= '</table>';
        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->Output('laporan_peminjaman.pdf', 'I');
    }

    public function excelPeminjaman(Request $request)
    {
        $query = DB::table('peminjaman_buku')
        ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
        ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
        ->select(
            'users.name as Nama_Peminjam',
            'books.judul as Judul_Buku',
            'peminjaman_buku.tanggal_pinjam as Tanggal_Pinjam',
            'peminjaman_buku.tanggal_kembali as Tanggal_Kembali',
            'peminjaman_buku.transaction_type as Jenis_Transaksi',
            'peminjaman_buku.status as Status'
        )
        ->whereNull('peminjaman_buku.deleted_at')
        ->orderBy('peminjaman_buku.id', 'desc');

        if ($request->from && $request->to) {
            $query->whereBetween('peminjaman_buku.tanggal_pinjam', [$request->from, $request->to . ' 23:59:59']);
        }

        $data = $query ->get()
        ->map(function ($item) {
            return (array) $item;
        })
        ->toArray();

        $filePath = storage_path('app/public/laporan_peminjaman.xlsx');

        SimpleExcelWriter::create($filePath)
        ->addRows($data);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    // ========================= LAPORAN BUKU MASUK =========================
    public function laporanMasuk(Request $request)
    {
        if (session('id') > 0) {
            $query = DB::table('data_masuk_buku')
            ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
            ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
            ->leftJoin('penulis', 'books.penulis_id', '=', 'penulis.id')
            ->select(
                'data_masuk_buku.id',
                'books.judul',
                'penulis.nama_penulis',
                'kategori.nama_kategori',
                'data_masuk_buku.tanggal_masuk',
                'data_masuk_buku.jumlah'
            )
            ->whereNull('data_masuk_buku.deleted_at')
            ->orderBy('data_masuk_buku.id', 'desc');

            if ($request->from && $request->to) {
                $query->whereBetween('data_masuk_buku.tanggal_masuk', [$request->from, $request->to . ' 23:59:59']);
            }

            $laporan = $query->get();

            return view('laporanmasuk', compact('laporan'));
        } else {
            return view('404');
        }
    }

    public function printMasuk(Request $request)
    {
        $query = DB::table('data_masuk_buku')
        ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
        ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
        ->select('books.judul', 'penulis.nama_penulis', 'data_masuk_buku.tanggal_masuk', 'data_masuk_buku.jumlah')
        ->whereNull('data_masuk_buku.deleted_at')
        ->orderBy('data_masuk_buku.id', 'desc');

        if ($request->from && $request->to) {
            $query->whereBetween('data_masuk_buku.tanggal_masuk', [$request->from, $request->to . ' 23:59:59']);
        }

        $result = $query->get();

        return view('print_masuk', ['query' => $result]);
    }   
    
    public function pdfMasuk(Request $request)
    {
        $query = DB::table('data_masuk_buku')
        ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
        ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
        ->select('books.judul', 'penulis.nama_penulis', 'data_masuk_buku.tanggal_masuk', 'data_masuk_buku.jumlah')
        ->whereNull('data_masuk_buku.deleted_at')
        ->orderBy('data_masuk_buku.id', 'desc');

        if ($request->from && $request->to) {
            $query->whereBetween('data_masuk_buku.tanggal_masuk', [$request->from, $request->to . ' 23:59:59']);
        }

        $result = $query->get();

        $pdf = Pdf::loadView('print_masuk', ['query' => $result]);
        return $pdf->download('laporan_buku_masuk.pdf');
    }

    public function excelMasuk(Request $request)
    {
        $query = DB::table('data_masuk_buku')
        ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
        ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
        ->select(
            'books.judul as Judul_Buku',
            'penulis.nama_penulis as Penulis',
            'data_masuk_buku.tanggal_masuk as Tanggal_Masuk',
            'data_masuk_buku.jumlah as Jumlah'
        )
        ->whereNull('data_masuk_buku.deleted_at')
        ->orderBy('data_masuk_buku.id', 'desc');

        if ($request->from && $request->to) {
            $query->whereBetween('data_masuk_buku.tanggal_masuk', [$request->from, $request->to . ' 23:59:59']);
        }

        $data = $query->get()
        ->map(function ($item) {
            return (array) $item;
        })
        ->toArray();

        $filePath = storage_path('app/public/laporan_buku_masuk.xlsx');

        SimpleExcelWriter::create($filePath)
        ->addRows($data);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
