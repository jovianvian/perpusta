@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                {{ $isSuperAdmin ? __('System Activity Log') : __('My Activity') }}
            </h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">
                {{ $isSuperAdmin ? __('Monitor all user activity, login, logout, and data changes.') : __('Your login history and account activity.') }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('activity.log') }}" class="p-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white transition-colors" title="{{ __('Refresh') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
        <form action="{{ route('activity.log') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}"
                        class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg pl-10 pr-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="{{ $isSuperAdmin ? __('Search name, IP, activity...') : __('Search your activity...') }}">
                </div>
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                {{ __('Filter') }}
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="js-smart-table w-full text-left text-sm text-slate-600 dark:text-slate-400" data-filter-fields="action">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs uppercase font-semibold text-slate-500 dark:text-slate-300">
                    <tr>
                        <th class="px-6 py-4">{{ __('Time') }}</th>
                        <th class="px-6 py-4">{{ __('User') }}</th>
                        <th class="px-6 py-4">{{ __('Activity') }}</th>
                        <th class="px-6 py-4">{{ __('Change Details') }}</th>
                        <th class="px-6 py-4">{{ __('Device Info') }}</th>
                        <th class="px-6 py-4">{{ __('Location (GPS)') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors" data-action="{{ strtolower($log->action_type ?? '') }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-slate-900 dark:text-white font-medium">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</div>
                            <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-xs">
                                    {{ substr($log->user_name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-slate-900 dark:text-white font-medium">{{ $log->user_name ?? __('Unknown User') }}</div>
                                    <div class="text-xs text-slate-500">{{ $log->user_email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badgeColor = 'bg-slate-700 text-slate-300';
                                if($log->action_type == 'login') $badgeColor = 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
                                if($log->action_type == 'logout') $badgeColor = 'bg-orange-500/10 text-orange-400 border border-orange-500/20';
                                if($log->action_type == 'create') $badgeColor = 'bg-blue-500/10 text-blue-400 border border-blue-500/20';
                                if($log->action_type == 'delete') $badgeColor = 'bg-red-500/10 text-red-400 border border-red-500/20';
                                if($log->action_type == 'update') $badgeColor = 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20';
                                if($log->action_type == 'location_ping') $badgeColor = 'bg-purple-500/10 text-purple-400 border border-purple-500/20';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                {{ strtoupper($log->action_type ?? __('General')) }}
                            </span>
                            <div class="text-xs text-slate-500 mt-1">{{ __('Table') }}: {{ $log->table_name }} #{{ $log->row_id }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-xs truncate" title="{{ $log->perubahan }}">
                            {{ Str::limit($log->perubahan, 50) }}
                        </td>
                        <td class="px-6 py-4">
                                <div class="text-xs">
                                <div class="mb-1"><span class="text-slate-500">IP:</span> {{ $log->ip_address ?? '-' }}</div>
                                <div title="{{ $log->user_agent }}"><span class="text-slate-500">{{ __('Agent') }}:</span> {{ Str::limit($log->user_agent, 20) ?? '-' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($log->latitude && $log->longitude)
                                <a href="https://www.google.com/maps?q={{ $log->latitude }},{{ $log->longitude }}" target="_blank" class="text-indigo-400 hover:text-indigo-300 text-xs flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ __('View Map') }}
                                </a>
                                <div class="text-[10px] text-slate-500 mt-0.5">{{ number_format($log->latitude, 5) }}, {{ number_format($log->longitude, 5) }}</div>
                            @elseif($log->action_type == 'location_ping')
                                <span class="text-yellow-500 text-xs">{{ __('Waiting GPS...') }}</span>
                            @else
                                <span class="text-slate-600 text-xs italic">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                            {{ __('No activity logs found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
            {{ $logs->appends(['q' => request('q')])->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
