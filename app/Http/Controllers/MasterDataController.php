<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    // ================= MAIN INDEX (Unified View) =================
    public function index(Request $request)
    {
        if (session('id') > 0) {
            // Fetch all data for the tabs
            $penulis = DB::table('penulis')->orderBy('nama_penulis', 'asc')->get();
            $kategori = DB::table('kategori')->orderBy('nama_kategori', 'asc')->get();
            $penerbit = DB::table('penerbit')->orderBy('nama_penerbit', 'asc')->get();
            
            // Determine active tab from request or default to 'penulis'
            $activeTab = $request->query('tab', 'penulis');
            
            return view('master_data', compact('penulis', 'kategori', 'penerbit', 'activeTab'));
        }
        return redirect()->login();
    }

    // ================= PENULIS =================
    public function storePenulis(Request $request)
    {
        $request->validate(['nama_penulis' => 'required']);
        DB::table('penulis')->insert([
            'nama_penulis' => $request->nama_penulis,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect('/master-data?tab=penulis')->with('success', 'Penulis berhasil ditambahkan');
    }

    public function updatePenulis(Request $request, $id)
    {
        $request->validate(['nama_penulis' => 'required']);
        DB::table('penulis')->where('id', $id)->update([
            'nama_penulis' => $request->nama_penulis,
            'updated_at' => now()
        ]);
        return redirect('/master-data?tab=penulis')->with('success', 'Penulis berhasil diperbarui');
    }

    public function deletePenulis($id)
    {
        // Cek ketergantungan
        $exists = DB::table('books')->where('penulis_id', $id)->exists();
        if ($exists) {
            return redirect('/master-data?tab=penulis')->with('error', 'Penulis tidak bisa dihapus karena masih memiliki buku.');
        }
        DB::table('penulis')->where('id', $id)->delete();
        return redirect('/master-data?tab=penulis')->with('success', 'Penulis berhasil dihapus');
    }

    // ================= KATEGORI =================
    public function storeKategori(Request $request)
    {
        $request->validate(['nama_kategori' => 'required']);
        DB::table('kategori')->insert([
            'nama_kategori' => $request->nama_kategori,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect('/master-data?tab=kategori')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function updateKategori(Request $request, $id)
    {
        $request->validate(['nama_kategori' => 'required']);
        DB::table('kategori')->where('id', $id)->update([
            'nama_kategori' => $request->nama_kategori,
            'updated_at' => now()
        ]);
        return redirect('/master-data?tab=kategori')->with('success', 'Kategori berhasil diperbarui');
    }

    public function deleteKategori($id)
    {
        $exists = DB::table('books')->where('kategori_id', $id)->exists();
        if ($exists) {
            return redirect('/master-data?tab=kategori')->with('error', 'Kategori tidak bisa dihapus karena masih digunakan buku.');
        }
        DB::table('kategori')->where('id', $id)->delete();
        return redirect('/master-data?tab=kategori')->with('success', 'Kategori berhasil dihapus');
    }

    // ================= PENERBIT =================
    public function storePenerbit(Request $request)
    {
        $request->validate(['nama_penerbit' => 'required']);
        DB::table('penerbit')->insert([
            'nama_penerbit' => $request->nama_penerbit,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect('/master-data?tab=penerbit')->with('success', 'Penerbit berhasil ditambahkan');
    }

    public function updatePenerbit(Request $request, $id)
    {
        $request->validate(['nama_penerbit' => 'required']);
        DB::table('penerbit')->where('id', $id)->update([
            'nama_penerbit' => $request->nama_penerbit,
            'updated_at' => now()
        ]);
        return redirect('/master-data?tab=penerbit')->with('success', 'Penerbit berhasil diperbarui');
    }

    public function deletePenerbit($id)
    {
        $exists = DB::table('books')->where('penerbit_id', $id)->exists();
        if ($exists) {
            return redirect('/master-data?tab=penerbit')->with('error', 'Penerbit tidak bisa dihapus karena masih digunakan buku.');
        }
        DB::table('penerbit')->where('id', $id)->delete();
        return redirect('/master-data?tab=penerbit')->with('success', 'Penerbit berhasil dihapus');
    }
}
