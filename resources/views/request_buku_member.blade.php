@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Request New Book') }}</h1>
    <p class="text-slate-500 dark:text-slate-400">{{ __('Can\'t find a book? Request it here!') }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Request -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Submit Request') }}</h3>
            <form action="{{ url('/request-buku') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-1">{{ __('Book Title') }}</label>
                        <input type="text" name="judul_buku" required class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent rounded-lg text-slate-900 dark:text-white py-2 px-3 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-1">{{ __('Author (Optional)') }}</label>
                        <input type="text" name="penulis" class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent rounded-lg text-slate-900 dark:text-white py-2 px-3 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-1">{{ __('Category (Optional)') }}</label>
                        <input type="text" name="kategori" class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent rounded-lg text-slate-900 dark:text-white py-2 px-3 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-1">{{ __('Description / Reason') }}</label>
                        <textarea name="deskripsi" rows="3" class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent rounded-lg text-slate-900 dark:text-white py-2 px-3 focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg transition-colors shadow-lg shadow-indigo-600/20">
                        {{ __('Send Request') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- History Request -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Your Requests') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="p-4">{{ __('Loan Date') }}</th>
                            <th class="p-4">{{ __('Book Title') }}</th>
                            <th class="p-4">{{ __('Status') }}</th>
                            <th class="p-4">Note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($myRequests as $req)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="p-4 text-slate-500 dark:text-slate-400 text-xs">{{ \Carbon\Carbon::parse($req->created_at)->format('d M Y') }}</td>
                            <td class="p-4 font-medium text-slate-900 dark:text-white">{{ $req->judul_buku }}</td>
                            <td class="p-4">
                                @if($req->status == 'pending')
                                    <span class="bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border border-yellow-500/20 rounded-full px-2 py-1 text-xs">{{ __('Pending Approval') }}</span>
                                @elseif($req->status == 'disetujui')
                                    <span class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 rounded-full px-2 py-1 text-xs">Approved</span>
                                @else
                                    <span class="bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20 rounded-full px-2 py-1 text-xs">Rejected</span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-500 dark:text-slate-400 text-sm">
                                @if($req->status == 'ditolak')
                                    <span class="text-red-600 dark:text-red-400">{{ $req->alasan_penolakan }}</span>
                                @elseif($req->status == 'disetujui')
                                    <span class="text-emerald-600 dark:text-emerald-400">{{ __('Processed soon!') }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-500 dark:text-slate-400">{{ __('No requests yet.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
