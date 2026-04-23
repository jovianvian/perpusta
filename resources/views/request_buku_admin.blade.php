@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Book Requests') }}</h1>
    <p class="text-slate-500 dark:text-slate-400">{{ __('Manage book requests from members') }}</p>
</div>

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="js-smart-table w-full text-left border-collapse" data-filter-fields="status,pemohon">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                <tr>
                    <th class="p-4">{{ __('Loan Date') }}</th>
                    <th class="p-4">{{ __('Members') }}</th>
                    <th class="p-4">{{ __('Book Title') }}</th>
                    <th class="p-4">{{ __('Description') }}</th>
                    <th class="p-4">{{ __('Status') }}</th>
                    <th class="p-4 text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($requests as $req)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50" data-status="{{ strtolower($req->status ?? '') }}" data-pemohon="{{ strtolower($req->pemohon ?? '') }}">
                    <td class="p-4 text-slate-500 dark:text-slate-400 text-xs">{{ \Carbon\Carbon::parse($req->created_at)->format('d M Y') }}</td>
                    <td class="p-4 text-slate-900 dark:text-white font-medium">{{ $req->pemohon }}</td>
                    <td class="p-4">
                        <div class="text-slate-900 dark:text-white font-bold">{{ $req->judul_buku }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $req->penulis ?? '-' }} | {{ $req->kategori ?? '-' }}
                        </div>
                    </td>
                    <td class="p-4 text-slate-600 dark:text-slate-300 text-sm italic">
                        "{{ $req->deskripsi ?? '-' }}"
                    </td>
                    <td class="p-4">
                        @if($req->status == 'pending')
                            <span class="bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border border-yellow-500/20 rounded-full px-2 py-1 text-xs">{{ __('Pending Approval') }}</span>
                        @elseif($req->status == 'disetujui')
                            <span class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 rounded-full px-2 py-1 text-xs">Approved</span>
                        @else
                            <span class="bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20 rounded-full px-2 py-1 text-xs">Rejected</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        @if($req->status == 'pending')
                        <div class="flex items-center justify-center gap-2">
                            <!-- Approve -->
                            <form action="{{ url('/admin/request-buku/' . $req->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="disetujui">
                                <button type="submit" class="p-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors" title="{{ __('Approve Loan') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>

                            <!-- Reject Modal Trigger -->
                            <button onclick="openRejectModal({{ $req->id }})" class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors" title="Reject">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        @else
                            <span class="text-slate-400 dark:text-slate-500 text-xs">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-500 dark:text-slate-400">{{ __('No new requests.') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Reject Request') }}</h3>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <input type="hidden" name="status" value="ditolak">
                <div class="px-6 py-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-2">{{ __('Reason for Rejection') }}</label>
                    <textarea name="alasan_penolakan" rows="3" required class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent rounded-lg text-slate-900 dark:text-white p-3 focus:ring-2 focus:ring-red-500" placeholder="e.g., Book is out of print..."></textarea>
                </div>
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-white border border-slate-300 dark:border-transparent rounded-lg">{{ __('Cancel') }}</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold">{{ __('Reject Request') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRejectModal(id) {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectForm').action = "{{ url('/admin/request-buku') }}/" + id;
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection
