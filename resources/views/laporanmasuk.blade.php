@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white">Laporan Buku Masuk</h1>
        <p class="text-slate-400">View and export incoming book reports</p>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-slate-800 rounded-xl shadow-lg border border-slate-700 p-6 mb-6">
    <form action="{{ url('/laporanmasuk') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
        <div>
            <label class="block text-sm font-medium text-slate-400 mb-2">From Date</label>
            <input type="date" name="from" value="{{ request('from') }}" class="w-full bg-slate-700 border-transparent rounded-lg text-white py-2.5 px-4 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-400 mb-2">To Date</label>
            <input type="date" name="to" value="{{ request('to') }}" class="w-full bg-slate-700 border-transparent rounded-lg text-white py-2.5 px-4 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-lg transition-colors flex items-center gap-2 shadow-lg shadow-indigo-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Filter
            </button>
            <a href="{{ url('/laporanmasuk') }}" class="bg-slate-700 hover:bg-slate-600 text-white font-medium py-2.5 px-6 rounded-lg transition-colors flex items-center justify-center">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Export Actions -->
<div class="flex flex-wrap gap-3 mb-6">
    <a href="{{ url('/laporanmasuk/print') }}?from={{ request('from') }}&to={{ request('to') }}" target="_blank" class="bg-slate-700 hover:bg-slate-600 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Print
    </a>
    <a href="{{ url('/laporanmasuk/pdf') }}?from={{ request('from') }}&to={{ request('to') }}" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
        Export PDF
    </a>
    <a href="{{ url('/laporanmasuk/excel') }}?from={{ request('from') }}&to={{ request('to') }}" class="bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Export Excel
    </a>
</div>

<!-- Table -->
<div class="bg-slate-800 rounded-xl shadow-lg border border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="tableLaporan">
            <thead class="bg-slate-900/50 text-slate-400 text-xs uppercase tracking-wide">
                <tr>
                    <th class="p-4 font-medium">No</th>
                    <th class="p-4 font-medium">Book Title</th>
                    <th class="p-4 font-medium">Author</th>
                    <th class="p-4 font-medium">Entry Date</th>
                    <th class="p-4 font-medium">Quantity</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700">
                @forelse($laporan as $l)
                <tr class="hover:bg-slate-700/50 transition-colors">
                    <td class="p-4 text-slate-400">{{ $loop->iteration }}</td>
                    <td class="p-4 text-indigo-400 font-medium">{{ $l->judul }}</td>
                    <td class="p-4 text-slate-300">{{ $l->nama_penulis ?? '-' }}</td>
                    <td class="p-4 text-slate-400 tgl-pinjam">{{ $l->tanggal_masuk }}</td>
                    <td class="p-4">
                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full px-3 py-1 text-xs font-medium">
                            + {{ $l->jumlah }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500">
                        No records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- No Data Message (Hidden by default, used by JS) -->
    <div id="noDataMessage" class="hidden p-8 text-center text-slate-500">
        No data found for the selected date range.
    </div>
</div>

<script>
    function filterTableByDate() {
        const fromDate = document.getElementById('filterFrom').value;
        const toDate = document.getElementById('filterTo').value;
        
        if (!fromDate || !toDate) {
            alert('Please select both From and To dates.');
            return;
        }

        const rows = document.querySelectorAll('#tableLaporan tbody tr');
        let hasVisibleRows = false;

        rows.forEach(row => {
            const dateCell = row.querySelector('.tgl-pinjam');
            if (!dateCell) return;
            
            // Assuming date format is YYYY-MM-DD
            const rowDateStr = dateCell.innerText.trim().split(' ')[0];
            
            if (rowDateStr >= fromDate && rowDateStr <= toDate) {
                row.style.display = '';
                hasVisibleRows = true;
            } else {
                row.style.display = 'none';
            }
        });

        const noDataMsg = document.getElementById('noDataMessage');
        if (!hasVisibleRows) {
            noDataMsg.classList.remove('hidden');
        } else {
            noDataMsg.classList.add('hidden');
        }
    }

    function resetFilter() {
        document.getElementById('filterFrom').value = '';
        document.getElementById('filterTo').value = '';
        
        const rows = document.querySelectorAll('#tableLaporan tbody tr');
        rows.forEach(row => row.style.display = '');
        
        document.getElementById('noDataMessage').classList.add('hidden');
    }
</script>
@endsection