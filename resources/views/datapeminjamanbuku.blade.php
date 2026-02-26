@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ __('Loans') }}</h1>
        <p class="text-slate-500 dark:text-slate-400">{{ __('Manage book loans and returns') }}</p>
    </div>

    <div class="flex gap-3">
        @if (session('level') == 6 || session('level') == 5)
            <button onclick="toggleHistoryLoan()" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-500 dark:text-blue-400 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors border border-blue-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                History
            </button>
            <button onclick="toggleTrashLoan()" class="bg-red-500/10 hover:bg-red-500/20 text-red-500 dark:text-red-400 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors border border-red-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Trash
            </button>
        @endif

        @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('peminjaman.create'))
        <button onclick="openLoanModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors shadow-lg shadow-indigo-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('New Loan') }}
        </button>
        @endif
    </div>
</div>

<!-- Main Table -->
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                <tr>
                    <th class="p-4 font-medium">{{ __('No') }}</th>
                    <th class="p-4 font-medium">{{ __('Borrower') }}</th>
                    <th class="p-4 font-medium">{{ __('Book Title') }}</th>
                    <th class="p-4 font-medium">{{ __('Loan Date') }}</th>
                    <th class="p-4 font-medium">{{ __('Return Date') }}</th>
                    <th class="p-4 font-medium">{{ __('Status') }}</th>
                    <th class="p-4 font-medium text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($data as $index => $item)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4 text-slate-500 dark:text-slate-400">{{ $index + 1 }}</td>
                    <td class="p-4 text-slate-600 dark:text-slate-300 font-medium">{{ $item->nama_peminjam }}</td>
                    <td class="p-4 text-slate-800 dark:text-white">{{ $item->judul_buku }}</td>
                    <td class="p-4 text-slate-500 dark:text-slate-400">{{ $item->tanggal_pinjam }}</td>
                    <td class="p-4 text-slate-500 dark:text-slate-400">{{ $item->tanggal_kembali ?? '-' }}</td>
                    <td class="p-4">
                        @if($item->status == 'dipinjam')
                            <span class="bg-amber-500/10 text-amber-500 dark:text-amber-400 border border-amber-500/20 rounded-full px-3 py-1 text-xs font-medium">{{ __('Active') }}</span>
                        @elseif($item->status == 'dikembalikan')
                            <span class="bg-emerald-500/10 text-emerald-500 dark:text-emerald-400 border border-emerald-500/20 rounded-full px-3 py-1 text-xs font-medium">{{ __('Returned') }}</span>
                        @elseif($item->status == 'pending_pinjam')
                            <span class="bg-blue-500/10 text-blue-500 dark:text-blue-400 border border-blue-500/20 rounded-full px-3 py-1 text-xs font-medium">{{ __('Pending Approval') }}</span>
                        @elseif($item->status == 'pending_kembali')
                            <span class="bg-purple-500/10 text-purple-500 dark:text-purple-400 border border-purple-500/20 rounded-full px-3 py-1 text-xs font-medium">{{ __('Request Return') }}</span>
                        @else
                            <span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-full px-3 py-1 text-xs font-medium">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex items-center justify-center gap-2">
                            <!-- APPROVAL ACTIONS -->
                            @if(app(\App\Helpers\PermissionHelper::class)->hasPermission('peminjaman.approve'))
                                @if($item->status == 'pending_pinjam')
                                    <a href="{{ url('/peminjaman/konfirmasi/' . $item->id . '/setujui_pinjam') }}" class="p-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors" title="{{ __('Approve Loan') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </a>
                                @elseif($item->status == 'pending_kembali')
                                    <button onclick="openReturnModal({{ $item->id }})" class="p-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors" title="{{ __('Approve Return') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                @elseif($item->status == 'dipinjam')
                                    <button onclick="openReturnModal({{ $item->id }})" class="p-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors" title="{{ __('Force Return') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
                                    </button>
                                @endif
                            @endif

                            <!-- EDIT -->
                            @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('peminjaman.update'))
                            <button onclick="editLoan({{ json_encode($item) }})" class="p-2 text-slate-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors" title="{{ __('Edit') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            @endif
                            
                            <!-- DELETE -->
                            @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('peminjaman.delete'))
                            <a href="{{ url('/peminjaman/hapus/' . $item->id) }}" onclick="return confirm('{{ __('Are you sure?') }}')" class="p-2 text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors" title="{{ __('Delete') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-slate-500">
                        {{ __('No active loans found.') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- History Container -->
<div id="historyContainer" class="hidden mt-8">
    <h2 class="text-xl font-bold text-blue-500 dark:text-blue-400 mb-4 flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Edit History (Revertable)
    </h2>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-blue-500/20 overflow-hidden transition-colors">
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
                    @if(isset($historyLoans) && count($historyLoans) > 0)
                        @foreach($historyLoans as $h)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="p-4 text-slate-800 dark:text-white">{{ $h->editor_name }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-300 text-sm">{{ $h->perubahan }}</td>
                            <td class="p-4 text-slate-500 dark:text-slate-400 text-xs">{{ $h->created_at }}</td>
                            <td class="p-4 text-center">
                                <a href="{{ url('/revert/' . $h->id) }}" onclick="return confirm('Revert changes?')" class="text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 text-sm font-medium">Revert</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4" class="p-4 text-center text-slate-500">No history found.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Trash Container -->
<div id="trashContainer" class="hidden mt-8">
    <h2 class="text-xl font-bold text-red-500 dark:text-red-400 mb-4 flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        Trash Bin (Soft Deleted)
    </h2>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-red-500/20 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="p-4">Borrower</th>
                        <th class="p-4">Book</th>
                        <th class="p-4">Deleted At</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @if(isset($deletedLoans) && count($deletedLoans) > 0)
                        @foreach($deletedLoans as $d)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="p-4 text-slate-800 dark:text-white">{{ $d->nama_peminjam }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-300">{{ $d->judul_buku }}</td>
                            <td class="p-4 text-slate-500 dark:text-slate-400 text-xs">{{ $d->deleted_at }}</td>
                            <td class="p-4 text-center flex justify-center gap-3">
                                <a href="{{ url('/restore/peminjaman/' . $d->id) }}" class="text-emerald-500 dark:text-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-300 font-medium text-sm">Restore</a>
                                <a href="{{ url('/force-delete/peminjaman/' . $d->id) }}" onclick="return confirm('Delete permanently?')" class="text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 font-medium text-sm">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4" class="p-4 text-center text-slate-500">No deleted items.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="loanModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeLoanModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold leading-6 text-slate-800 dark:text-white" id="modalTitle">Add New Loan</h3>
                <button type="button" onclick="closeLoanModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="loanForm" method="POST" action="">
                @csrf
                <input type="hidden" name="id" id="loanId">
                <div id="methodContainer"></div>
                
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Borrower (User)</label>
                        <select name="user_id" id="user_id" required class="w-full bg-slate-100 dark:bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white py-2.5 px-4 transition-colors">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Book</label>
                        <select name="book_id" id="book_id" required class="w-full bg-slate-100 dark:bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white py-2.5 px-4 transition-colors">
                            <option value="">-- Select Book --</option>
                            @foreach($books as $book)
                            <option value="{{ $book->id }}">{{ $book->judul }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Loan Date</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" required class="w-full bg-slate-100 dark:bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white py-2.5 px-4 transition-colors">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Return Date</label>
                        <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="w-full bg-slate-100 dark:bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white py-2.5 px-4 transition-colors">
                        <p class="text-xs text-slate-500 mt-1">Leave empty if not yet returned</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Status</label>
                        <select name="status" id="status" required class="w-full bg-slate-100 dark:bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white py-2.5 px-4 transition-colors">
                            <option value="Dipinjam">Dipinjam (Active)</option>
                            <option value="Dikembalikan">Dikembalikan (Returned)</option>
                        </select>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-200 dark:border-slate-700 transition-colors">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors shadow-lg shadow-indigo-600/20">Save</button>
                    <button type="button" onclick="closeLoanModal()" class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white font-medium py-2 px-4 rounded-lg transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Return Modal -->
<div id="returnModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeReturnModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Process Return</h3>
            </div>
            <form id="returnForm" method="GET">
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Book Condition</label>
                        <select name="kondisi" id="kondisi" class="w-full bg-slate-100 dark:bg-slate-700 border-transparent rounded-lg text-slate-900 dark:text-white p-2.5 focus:ring-2 focus:ring-indigo-500 transition-colors" onchange="checkCondition()">
                            <option value="baik">Good (Normal Return)</option>
                            <option value="rusak">Damaged (Fine Applies)</option>
                            <option value="hilang">Lost (Fine Applies)</option>
                        </select>
                    </div>

                    <div id="dendaContainer" class="hidden">
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Additional Fine (Rp)</label>
                        <input type="number" name="denda_tambahan" id="denda_tambahan" class="w-full bg-slate-100 dark:bg-slate-700 border-transparent rounded-lg text-slate-900 dark:text-white p-2.5 focus:ring-2 focus:ring-red-500 transition-colors" placeholder="Enter fine amount">
                        <p class="text-xs text-slate-500 mt-1">Fine for late return will be calculated automatically.</p>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 transition-colors">
                    <button type="button" onclick="closeReturnModal()" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white rounded-lg transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold">Process Return</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openReturnModal(id) {
        document.getElementById('returnModal').classList.remove('hidden');
        // Set form action dynamically
        document.getElementById('returnForm').action = "{{ url('/peminjaman/konfirmasi') }}/" + id + "/setujui_kembali";
        // Reset form
        document.getElementById('kondisi').value = 'baik';
        document.getElementById('denda_tambahan').value = '';
        checkCondition();
    }

    function closeReturnModal() {
        document.getElementById('returnModal').classList.add('hidden');
    }

    function checkCondition() {
        const kondisi = document.getElementById('kondisi').value;
        const dendaContainer = document.getElementById('dendaContainer');
        if (kondisi === 'rusak' || kondisi === 'hilang') {
            dendaContainer.classList.remove('hidden');
            document.getElementById('denda_tambahan').required = true;
        } else {
            dendaContainer.classList.add('hidden');
            document.getElementById('denda_tambahan').required = false;
            document.getElementById('denda_tambahan').value = '';
        }
    }

    function openLoanModal() {
        document.getElementById('loanModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Add New Loan';
        document.getElementById('loanForm').action = "{{ url('/peminjaman/store') }}";
        document.getElementById('methodContainer').innerHTML = '';
        document.getElementById('loanForm').reset();
        
        // Default date today
        document.getElementById('tanggal_pinjam').value = new Date().toISOString().split('T')[0];
    }

    function editLoan(item) {
        document.getElementById('loanModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Edit Loan';
        document.getElementById('loanForm').action = "{{ url('/peminjaman/update') }}"; // Fixed: remove ID from URL for this specific route
        document.getElementById('loanId').value = item.id;
        
        // Route is POST, no need for PUT spoofing
        document.getElementById('methodContainer').innerHTML = '';
        
        document.getElementById('user_id').value = item.user_id;
        document.getElementById('book_id').value = item.book_id;
        document.getElementById('tanggal_pinjam').value = item.tanggal_pinjam;
        document.getElementById('tanggal_kembali').value = item.tanggal_kembali;
        
        // Case insensitive status check or direct value
        let statusVal = item.status;
        // Ensure capitalizing first letter if needed, though value usually matches
        document.getElementById('status').value = statusVal;
    }

    function closeLoanModal() {
        document.getElementById('loanModal').classList.add('hidden');
    }

    function toggleHistoryLoan() {
        document.getElementById('historyContainer').classList.toggle('hidden');
        document.getElementById('trashContainer').classList.add('hidden');
    }

    function toggleTrashLoan() {
        document.getElementById('trashContainer').classList.toggle('hidden');
        document.getElementById('historyContainer').classList.add('hidden');
    }
</script>
@endsection