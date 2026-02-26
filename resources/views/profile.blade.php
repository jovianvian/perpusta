@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">Profil Saya</h1>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-6">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Profile Info -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">WhatsApp</label>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" placeholder="08xxxxxxxxxx">
                                <p class="text-xs text-slate-500 mt-1">Digunakan untuk notifikasi peminjaman.</p>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="space-y-4 bg-slate-50 dark:bg-slate-700/50 p-4 rounded-lg">
                            <h3 class="font-semibold text-slate-800 dark:text-white">Ganti Password</h3>
                            <p class="text-xs text-slate-500 mb-3">Kosongkan jika tidak ingin mengubah password.</p>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password Baru</label>
                                <input type="password" name="password" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection