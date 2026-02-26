@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Incoming Books</h1>
        <p class="text-slate-500 dark:text-slate-400">Track book inventory additions</p>
    </div>
    
    <div class="flex gap-3">
        @if (session('level') == 6)
            <!-- Super Admin Actions -->
            <button onclick="toggleHistoryMasuk()" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors border border-blue-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                History
            </button>
            <button onclick="toggleTrashMasuk()" class="bg-red-500/10 hover:bg-red-500/20 text-red-600 dark:text-red-400 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors border border-red-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Trash
            </button>
        @endif

        @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('buku-masuk.create'))
        <button onclick="openAddModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors shadow-lg shadow-indigo-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Incoming
        </button>
        @endif
    </div>
</div>

<!-- Main Table -->
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                <tr>
                    <th class="p-4 font-medium">No</th>
                    <th class="p-4 font-medium">Book Title</th>
                    <th class="p-4 font-medium">Quantity</th>
                    <th class="p-4 font-medium">Entry Date</th>
                    <th class="p-4 font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($data as $index => $item)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4 text-slate-500 dark:text-slate-400">{{ $index + 1 }}</td>
                    <td class="p-4 font-medium text-slate-900 dark:text-white">{{ $item->judul }}</td>
                    <td class="p-4">
                        <span class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 rounded-full px-3 py-1 text-xs font-medium">
                            + {{ $item->jumlah }}
                        </span>
                    </td>
                    <td class="p-4 text-slate-600 dark:text-slate-300">{{ $item->tanggal_masuk }}</td>
                    <td class="p-4">
                        <div class="flex items-center justify-center gap-2">
                            @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('buku-masuk.update'))
                            <button onclick="editDataMasuk({{ json_encode($item) }})" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            @endif
                            
                            @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('buku-masuk.delete'))
                            <a href="{{ url('/datamasuk/hapus/' . $item->id) }}" onclick="return confirm('Are you sure?')" class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        No records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- History Container -->
<div id="historyContainer" class="hidden mt-8">
    <h2 class="text-xl font-bold text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Edit History (Revertable)
    </h2>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-blue-500/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="p-4">Editor</th>
                        <th class="p-4">Changes</th>
                        <th class="p-4">Date</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @if(isset($historyIncoming) && count($historyIncoming) > 0)
                        @foreach($historyIncoming as $h)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="p-4 text-slate-900 dark:text-white">{{ $h->editor_name }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-300 text-sm">{{ $h->perubahan }}</td>
                            <td class="p-4 text-slate-500 dark:text-slate-400 text-xs">{{ $h->created_at }}</td>
                            <td class="p-4 text-center">
                                <a href="{{ url('/revert/' . $h->id) }}" onclick="return confirm('Revert changes?')" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 text-sm font-medium">Revert</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4" class="p-4 text-center text-slate-500 dark:text-slate-400">No history found.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Trash Container -->
<div id="trashContainer" class="hidden mt-8">
    <h2 class="text-xl font-bold text-red-600 dark:text-red-400 mb-4 flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        Trash Bin (Soft Deleted)
    </h2>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-red-500/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="p-4">Book Title</th>
                        <th class="p-4">Quantity</th>
                        <th class="p-4">Deleted At</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @if(isset($deletedIncoming) && count($deletedIncoming) > 0)
                        @foreach($deletedIncoming as $d)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="p-4 text-slate-900 dark:text-white">{{ $d->judul }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-300">{{ $d->jumlah }}</td>
                            <td class="p-4 text-slate-500 dark:text-slate-400 text-xs">{{ $d->deleted_at }}</td>
                            <td class="p-4 text-center flex justify-center gap-3">
                                <a href="{{ url('/restore/incoming/' . $d->id) }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 dark:hover:text-emerald-300 font-medium text-sm">Restore</a>
                                <a href="{{ url('/force-delete/incoming/' . $d->id) }}" onclick="return confirm('Delete permanently?')" class="text-red-600 dark:text-red-400 hover:text-red-500 dark:hover:text-red-300 font-medium text-sm">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4" class="p-4 text-center text-slate-500 dark:text-slate-400">No deleted items.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="dataModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold leading-6 text-slate-900 dark:text-white" id="modalTitle">Add Incoming Book</h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-500 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="modalForm" method="POST" action="">
                @csrf
                <div id="methodInputContainer"></div>
                
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-2">Select Book</label>
                        <select name="book_id" id="inputBookId" required class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white py-2.5 px-4">
                            <option value="">-- Select Book --</option>
                            @foreach($books as $book)
                            <option value="{{ $book->id }}">{{ $book->judul }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-2">Quantity</label>
                        <input type="number" name="jumlah" id="inputJumlah" required min="1" class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-2">Entry Date</label>
                        <input type="date" name="tanggal_masuk" id="inputTanggal" required class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white py-2.5 px-4">
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-200 dark:border-slate-700">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors shadow-lg shadow-indigo-600/20">Save</button>
                    <button type="button" onclick="closeModal()" class="bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-white font-medium py-2 px-4 rounded-lg transition-colors border border-slate-300 dark:border-transparent">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('dataModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Add Incoming Book';
        document.getElementById('modalForm').action = "{{ url('/datamasuk/store') }}";
        document.getElementById('methodInputContainer').innerHTML = '';
        document.getElementById('modalForm').reset();
        
        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('inputTanggal').value = today;
    }

    function editDataMasuk(item) {
        document.getElementById('dataModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Edit Incoming Data';
        document.getElementById('modalForm').action = "{{ url('/datamasuk/update') }}/" + item.id;
        
        // Laravel PUT method spoofing
        document.getElementById('methodInputContainer').innerHTML = '<input type="hidden" name="_method" value="PUT"><input type="hidden" name="id" value="' + item.id + '">';
        
        document.getElementById('inputBookId').value = item.book_id;
        document.getElementById('inputJumlah').value = item.jumlah;
        document.getElementById('inputTanggal').value = item.tanggal_masuk;
    }

    function closeModal() {
        document.getElementById('dataModal').classList.add('hidden');
    }

    function toggleHistoryMasuk() {
        document.getElementById('historyContainer').classList.toggle('hidden');
        document.getElementById('trashContainer').classList.add('hidden');
    }

    function toggleTrashMasuk() {
        document.getElementById('trashContainer').classList.toggle('hidden');
        document.getElementById('historyContainer').classList.add('hidden');
    }
</script>
@endsection