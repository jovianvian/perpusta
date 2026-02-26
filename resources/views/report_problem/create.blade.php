@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">Lapor Masalah</h1>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-6">
                <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Deskripsi Masalah</label>
                            <textarea name="description" rows="5" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" placeholder="Jelaskan masalah yang Anda temukan (misal: Buku rusak, Halaman hilang, Bug aplikasi, dll)..." required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Foto Bukti (Opsional)</label>
                            <input type="file" name="photo_proof" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-slate-300">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
