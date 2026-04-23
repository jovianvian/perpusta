<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Elibyy\TCPDF\Facades\TCPDF;
use Spatie\SimpleExcel\SimpleExcelWriter;

class DataController extends Controller
{
    public function books(Request $request)
    {
        $query = DB::table('books')
        ->join('penulis', 'books.penulis_id', '=', 'penulis.id')
        ->join('penerbit', 'books.penerbit_id', '=', 'penerbit.id')
        ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
        ->select('books.*', 'penulis.nama_penulis', 'penerbit.nama_penerbit', 'kategori.nama_kategori');

        if ($request->has('trash') && $request->trash == 1) {
            $query->whereNotNull('books.deleted_at')
                  ->leftJoin('users as deleter', 'books.deleted_by', '=', 'deleter.id')
                  ->addSelect('deleter.name as deleted_by_name');
        } else {
            $query->whereNull('books.deleted_at');
        }

        $buku = $query->orderBy('books.judul', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $buku
        ]);
    }

    public function dataMasukBuku(Request $request)
    {
        if (session('id') > 0) {
            $query = DB::table('data_masuk_buku')
            ->join('books', 'data_masuk_buku.book_id', '=', 'books.id')
            ->select(
                'data_masuk_buku.id',
                'books.judul',
                'data_masuk_buku.jumlah',
                'data_masuk_buku.tanggal_masuk',
                'data_masuk_buku.book_id'
            );

            if ($request->has('trash') && $request->trash == 1) {
                $query->whereNotNull('data_masuk_buku.deleted_at')
                      ->leftJoin('users as deleter', 'data_masuk_buku.deleted_by', '=', 'deleter.id')
                      ->addSelect('deleter.name as deleted_by_name', 'data_masuk_buku.deleted_at');
            } else {
                $query->whereNull('data_masuk_buku.deleted_at');
            }

            $data = $query->orderBy('data_masuk_buku.tanggal_masuk', 'desc')->get();

            $books = DB::table('books')->whereNull('deleted_at')->select('id', 'judul')->get();

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'options_buku' => $books
            ]);
        }
    }

    public function peminjaman(Request $request)
    {
        if (session('id') > 0) {
            $query = DB::table('peminjaman_buku')
            ->join('books', 'peminjaman_buku.book_id', '=', 'books.id')
            ->leftJoin('kategori', 'books.kategori_id', '=', 'kategori.id')
            ->join('users', 'peminjaman_buku.user_id', '=', 'users.id')
            ->select(
                'peminjaman_buku.id',
                'books.judul',
                'users.name',
                'peminjaman_buku.tanggal_pinjam',
                'peminjaman_buku.tanggal_kembali',
                'peminjaman_buku.status',
                'peminjaman_buku.book_id',
                'peminjaman_buku.user_id'
            );

            if ($request->has('trash') && $request->trash == 1) {
                $query->whereNotNull('peminjaman_buku.deleted_at')
                      ->leftJoin('users as deleter', 'peminjaman_buku.deleted_by', '=', 'deleter.id')
                      ->addSelect('deleter.name as deleted_by_name', 'peminjaman_buku.deleted_at');
            } else {
                $query->whereNull('peminjaman_buku.deleted_at');
            }

            $data = $query->orderBy('peminjaman_buku.tanggal_pinjam', 'desc')->get();

            $books = DB::table('books')->whereNull('deleted_at')->select('id', 'judul')->get();
            $users = DB::table('users')->whereNull('deleted_at')->select('id', 'name')->get();

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'opt_buku' => $books,
                'opt_user' => $users
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 401);
    }

    public function dataUser(Request $request)
    {
        if (session('id') > 0) {
            $actor = DB::table('users')->where('id', session('id'))->first();
            $superAdminLevelId = DB::table('levels')
                ->whereRaw('LOWER(nama_level) = ?', ['super admin'])
                ->value('id');
            $actorIsSuperAdmin = $actor && $superAdminLevelId && (int) $actor->level_id === (int) $superAdminLevelId;

            $query = DB::table('users')
            ->leftJoin('levels', 'users.level_id', '=', 'levels.id')
            ->select('users.*', 'levels.nama_level')
            ->when(!$actorIsSuperAdmin && $superAdminLevelId, function ($builder) use ($superAdminLevelId) {
                $builder->where('users.level_id', '!=', $superAdminLevelId);
            });

            if ($request->has('trash') && $request->trash == 1) {
                $query->whereNotNull('users.deleted_at')
                      ->leftJoin('users as deleter', 'users.deleted_by', '=', 'deleter.id')
                      ->addSelect('deleter.name as deleted_by_name');
            } else {
                $query->whereNull('users.deleted_at');
            }

            $users = $query->orderBy('users.name', 'asc')->get();

            $levels = DB::table('levels')
                ->select('id', 'nama_level')
                ->when(!$actorIsSuperAdmin && $superAdminLevelId, function ($builder) use ($superAdminLevelId) {
                    $builder->where('id', '!=', $superAdminLevelId);
                })
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $users,
                'opt_level' => $levels
            ]);
        }
    }

    protected function ensureSuperAdmin()
    {
        if (!session('id')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = DB::table('users')->where('id', session('id'))->first();
        if (!$user || !in_array((int) $user->level_id, [5, 6], true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden'
            ], 403);
        }

        return null;
    }

    public function historyBooks()
    {
        $guard = $this->ensureSuperAdmin();
        if ($guard) {
            return $guard;
        }

        $data = DB::table('edit_histories')
            ->leftJoin('books', function ($join) {
                $join->on('edit_histories.row_id', '=', 'books.id')
                    ->where('edit_histories.table_name', '=', 'books');
            })
            ->leftJoin('users', 'edit_histories.edited_by', '=', 'users.id')
            ->select(
                'edit_histories.id',
                'books.judul',
                'edit_histories.perubahan',
                'edit_histories.old_values',
                'users.name as edited_by_name',
                'edit_histories.created_at'
            )
            ->where('edit_histories.table_name', 'books')
            ->orderBy('edit_histories.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function historyMasuk()
    {
        $guard = $this->ensureSuperAdmin();
        if ($guard) {
            return $guard;
        }

        $data = DB::table('edit_histories')
            ->leftJoin('data_masuk_buku', function ($join) {
                $join->on('edit_histories.row_id', '=', 'data_masuk_buku.id')
                    ->where('edit_histories.table_name', '=', 'data_masuk_buku');
            })
            ->leftJoin('books', 'data_masuk_buku.book_id', '=', 'books.id')
            ->leftJoin('users', 'edit_histories.edited_by', '=', 'users.id')
            ->select(
                'edit_histories.id',
                'books.judul',
                'edit_histories.perubahan',
                'edit_histories.old_values',
                'users.name as edited_by_name',
                'edit_histories.created_at'
            )
            ->where('edit_histories.table_name', 'data_masuk_buku')
            ->orderBy('edit_histories.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function historyPeminjaman()
    {
        $guard = $this->ensureSuperAdmin();
        if ($guard) {
            return $guard;
        }

        $data = DB::table('edit_histories')
            ->leftJoin('peminjaman_buku', function ($join) {
                $join->on('edit_histories.row_id', '=', 'peminjaman_buku.id')
                    ->where('edit_histories.table_name', '=', 'peminjaman_buku');
            })
            ->leftJoin('books', 'peminjaman_buku.book_id', '=', 'books.id')
            ->leftJoin('users as peminjam', 'peminjaman_buku.user_id', '=', 'peminjam.id')
            ->leftJoin('users as editor', 'edit_histories.edited_by', '=', 'editor.id')
            ->select(
                'edit_histories.id',
                'books.judul',
                'peminjam.name as nama_peminjam',
                'edit_histories.perubahan',
                'edit_histories.old_values',
                'editor.name as edited_by_name',
                'edit_histories.created_at'
            )
            ->where('edit_histories.table_name', 'peminjaman_buku')
            ->orderBy('edit_histories.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function historyUser()
    {
        $guard = $this->ensureSuperAdmin();
        if ($guard) {
            return $guard;
        }

        $data = DB::table('edit_histories')
            ->leftJoin('users as target', function ($join) {
                $join->on('edit_histories.row_id', '=', 'target.id')
                    ->where('edit_histories.table_name', '=', 'users');
            })
            ->leftJoin('users as editor', 'edit_histories.edited_by', '=', 'editor.id')
            ->select(
                'edit_histories.id',
                'target.name as nama_user',
                'edit_histories.perubahan',
                'edit_histories.old_values',
                'editor.name as edited_by_name',
                'edit_histories.created_at'
            )
            ->where('edit_histories.table_name', 'users')
            ->orderBy('edit_histories.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function riwayatPeminjaman()
    {
        if (session('id') > 0) {
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

            return response()->json([
                'status' => 'success',
                'data' => $riwayat
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 401);
    }
}
