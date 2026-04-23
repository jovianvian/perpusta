@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Trash Bin') }}</h1>
    <p class="text-slate-600 dark:text-slate-400">{{ __('Restore deleted items or permanently remove them.') }}</p>
</div>

@if(session('success'))
<div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-lg flex items-center gap-3">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    {{ session('success') }}
</div>
@endif

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="border-b border-slate-200 dark:border-slate-700">
        <nav class="flex -mb-px" aria-label="Tabs">
            <button onclick="switchTab('books')" id="tab-books" class="tab-btn w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm border-indigo-500 text-indigo-400">
                Books ({{ count($books) }})
            </button>
            <button onclick="switchTab('users')" id="tab-users" class="tab-btn w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300">
                Users ({{ count($users) }})
            </button>
            <button onclick="switchTab('loans')" id="tab-loans" class="tab-btn w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300">
                Loans ({{ count($loans) }})
            </button>
            <button onclick="switchTab('incoming')" id="tab-incoming" class="tab-btn w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300">
                Incoming ({{ count($incoming) }})
            </button>
        </nav>
    </div>

    <div class="p-6">
        <!-- Books Table -->
        <div id="content-books" class="tab-content">
            @if(count($books) > 0)
            <table class="js-smart-table w-full text-left border-collapse" data-filter-fields="jenis">
                <thead class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr data-jenis="book">
                        <th class="p-3">Title</th>
                        <th class="p-3">Deleted At</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($books as $item)
                    <tr>
                        <td class="p-3 text-slate-900 dark:text-white">{{ $item->judul }}</td>
                        <td class="p-3 text-slate-400">{{ $item->deleted_at }}</td>
                        <td class="p-3 text-right space-x-2">
                            <a href="{{ url('/restore/book/'.$item->id) }}" class="text-emerald-400 hover:text-emerald-300 text-sm">Restore</a>
                            <a href="{{ url('/force-delete/book/'.$item->id) }}" onclick="return confirm('{{ __('Permanent delete?') }}')" class="text-red-400 hover:text-red-300 text-sm">{{ __('Delete') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-slate-500 italic text-center py-4">{{ __('No deleted books.') }}</p>
            @endif
        </div>

        <!-- Users Table -->
        <div id="content-users" class="tab-content hidden">
            @if(count($users) > 0)
            <table class="js-smart-table w-full text-left border-collapse" data-filter-fields="jenis">
                <thead class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr data-jenis="user">
                        <th class="p-3">Name</th>
                        <th class="p-3">Email</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($users as $item)
                    <tr>
                        <td class="p-3 text-slate-900 dark:text-white">{{ $item->name }}</td>
                        <td class="p-3 text-slate-400">{{ $item->email }}</td>
                        <td class="p-3 text-right space-x-2">
                            <a href="{{ url('/restore/user/'.$item->id) }}" class="text-emerald-400 hover:text-emerald-300 text-sm">Restore</a>
                            <a href="{{ url('/force-delete/user/'.$item->id) }}" onclick="return confirm('{{ __('Permanent delete?') }}')" class="text-red-400 hover:text-red-300 text-sm">{{ __('Delete') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-slate-500 italic text-center py-4">{{ __('No deleted users.') }}</p>
            @endif
        </div>

        <!-- Loans Table -->
        <div id="content-loans" class="tab-content hidden">
            @if(count($loans) > 0)
            <table class="js-smart-table w-full text-left border-collapse" data-filter-fields="jenis">
                <thead class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr data-jenis="loan">
                        <th class="p-3">ID</th>
                        <th class="p-3">Deleted At</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($loans as $item)
                    <tr>
                        <td class="p-3 text-slate-900 dark:text-white">Loan #{{ $item->id }}</td>
                        <td class="p-3 text-slate-400">{{ $item->deleted_at }}</td>
                        <td class="p-3 text-right space-x-2">
                            <a href="{{ url('/restore/peminjaman/'.$item->id) }}" class="text-emerald-400 hover:text-emerald-300 text-sm">Restore</a>
                            <a href="{{ url('/force-delete/peminjaman/'.$item->id) }}" onclick="return confirm('{{ __('Permanent delete?') }}')" class="text-red-400 hover:text-red-300 text-sm">{{ __('Delete') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-slate-500 italic text-center py-4">{{ __('No deleted loans.') }}</p>
            @endif
        </div>

        <!-- Incoming Table -->
        <div id="content-incoming" class="tab-content hidden">
            @if(count($incoming) > 0)
            <table class="js-smart-table w-full text-left border-collapse" data-filter-fields="jenis">
                <thead class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr data-jenis="incoming">
                        <th class="p-3">ID</th>
                        <th class="p-3">Deleted At</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($incoming as $item)
                    <tr>
                        <td class="p-3 text-slate-900 dark:text-white">Entry #{{ $item->id }}</td>
                        <td class="p-3 text-slate-400">{{ $item->deleted_at }}</td>
                        <td class="p-3 text-right space-x-2">
                            <a href="{{ url('/restore/datamasuk/'.$item->id) }}" class="text-emerald-400 hover:text-emerald-300 text-sm">Restore</a>
                            <a href="{{ url('/force-delete/datamasuk/'.$item->id) }}" onclick="return confirm('{{ __('Permanent delete?') }}')" class="text-red-400 hover:text-red-300 text-sm">{{ __('Delete') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-slate-500 italic text-center py-4">{{ __('No deleted incoming data.') }}</p>
            @endif
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // Show selected content
        document.getElementById('content-' + tab).classList.remove('hidden');
        
        // Reset all tabs
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-indigo-500', 'text-indigo-400');
            el.classList.add('border-transparent', 'text-slate-500', 'dark:text-slate-400');
        });
        
        // Highlight active tab
        const activeBtn = document.getElementById('tab-' + tab);
        activeBtn.classList.remove('border-transparent', 'text-slate-500', 'dark:text-slate-400');
        activeBtn.classList.add('border-indigo-500', 'text-indigo-400');
    }
</script>
@endsection
