@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Search Results') }}</h1>
    <p class="text-slate-600 dark:text-slate-400">{{ __('Showing results for keyword:') }} <span class="text-indigo-500 dark:text-indigo-400 font-bold">"{{ $q }}"</span></p>
</div>

<div class="space-y-8">
    
    <!-- Books Results -->
    <div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            {{ __('Books') }} ({{ count($books) }})
        </h2>
        @if(count($books) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($books as $book)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-4 flex gap-4">
                <div class="w-16 h-24 bg-slate-100 dark:bg-slate-700 rounded-md flex-shrink-0 overflow-hidden">
                    @if($book->foto)
                        <img src="{{ Storage::url($book->foto) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white line-clamp-2">{{ $book->judul }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('Stock') }}: {{ $book->stok }}</p>
                    <a href="{{ url('/databuku') }}" class="text-xs text-indigo-500 dark:text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 mt-2 block">{{ __('View Detail') }} &rarr;</a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-slate-500 italic">{{ __('No books found.') }}</p>
        @endif
    </div>

    <!-- Users Results -->
    <div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            {{ __('Users') }} ({{ count($users) }})
        </h2>
        @if(count($users) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($users as $user)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-900 dark:text-white font-bold text-lg">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                    <a href="{{ url('/datauser') }}" class="text-xs text-indigo-500 dark:text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 mt-1 block">{{ __('View Profile') }} &rarr;</a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-slate-500 italic">{{ __('No users found.') }}</p>
        @endif
    </div>

    <!-- Loans Results -->
    <div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            {{ __('Loan Transactions') }} ({{ count($loans) }})
        </h2>
        @if(count($loans) > 0)
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
            <table class="js-smart-table w-full text-left border-collapse" data-filter-fields="status">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="p-4">{{ __('Borrower') }}</th>
                        <th class="p-4">{{ __('Book') }}</th>
                        <th class="p-4">{{ __('Loan Date') }}</th>
                        <th class="p-4">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($loans as $loan)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50" data-status="{{ strtolower($loan->status ?? '') }}">
                        <td class="p-4 text-slate-900 dark:text-white font-medium">{{ $loan->name }}</td>
                        <td class="p-4 text-slate-700 dark:text-slate-300">{{ $loan->judul }}</td>
                        <td class="p-4 text-slate-500 dark:text-slate-400">{{ $loan->tanggal_pinjam }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $loan->status == 'dipinjam' ? 'bg-amber-500/10 text-amber-400' : 'bg-emerald-500/10 text-emerald-400' }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-slate-500 italic">{{ __('No loan data found.') }}</p>
        @endif
    </div>

</div>
@endsection
