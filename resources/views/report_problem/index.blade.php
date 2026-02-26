@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Daftar Laporan Masalah</h1>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Pelapor</th>
                        <th class="p-4">Deskripsi</th>
                        <th class="p-4">Bukti</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Admin Note</th>
                        <th class="p-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="p-4 text-sm text-slate-500">{{ \Carbon\Carbon::parse($report->created_at)->format('d M Y H:i') }}</td>
                        <td class="p-4 font-medium text-slate-800 dark:text-white">{{ $report->reporter_name }}</td>
                        <td class="p-4 text-sm text-slate-600 dark:text-slate-300 max-w-xs truncate">{{ $report->description }}</td>
                        <td class="p-4">
                            @if($report->photo_proof)
                                <a href="{{ asset('storage/' . $report->photo_proof) }}" target="_blank" class="text-indigo-500 hover:underline text-xs">Lihat Foto</a>
                            @else
                                <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="p-4">
                            @if($report->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                            @elseif($report->status == 'processed')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Diproses</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Ditolak</span>
                            @endif
                        </td>
                        <td class="p-4 text-sm text-slate-500">{{ $report->admin_note ?? '-' }}</td>
                        <td class="p-4">
                            <button onclick="openStatusModal({{ json_encode($report) }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium text-sm">Update</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-slate-500">Belum ada laporan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div id="statusModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeStatusModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Update Status Laporan</h3>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status</label>
                        <select name="status" id="statusSelect" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                            <option value="pending">Pending</option>
                            <option value="processed">Diproses / Selesai</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Catatan Admin</label>
                        <textarea name="admin_note" id="adminNote" rows="3" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white"></textarea>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-200 dark:border-slate-700">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Simpan</button>
                    <button type="button" onclick="closeStatusModal()" class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white font-medium py-2 px-4 rounded-lg">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openStatusModal(report) {
        document.getElementById('statusModal').classList.remove('hidden');
        document.getElementById('statusForm').action = "/admin/reports/" + report.id;
        document.getElementById('statusSelect').value = report.status;
        document.getElementById('adminNote').value = report.admin_note || '';
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }
</script>
@endsection
