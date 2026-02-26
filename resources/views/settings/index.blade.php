@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white">Role Permission Settings</h1>
        <p class="text-slate-400">Manage access rights for each user role</p>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center gap-3">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    {{ session('success') }}
</div>
@endif

<!-- Glassy Table Container -->
<div class="bg-slate-800 rounded-xl shadow-lg border border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-900/50 text-slate-400 text-xs uppercase tracking-wide">
                <tr>
                    <th class="p-4 font-medium text-center w-16">No</th>
                    <th class="p-4 font-medium">Role Name / Level</th>
                    <th class="p-4 font-medium">Permission Status</th>
                    <th class="p-4 font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700">
                @foreach($levels as $index => $item)
                <tr class="hover:bg-slate-700/50 transition-colors">
                    <td class="p-4 text-slate-400 text-center">{{ $index + 1 }}</td>
                    <td class="p-4">
                        <span class="font-bold text-lg text-white">{{ $item->nama_level }}</span>
                    </td>
                    <td class="p-4">
                        @if($item->id == 5)
                            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full px-3 py-1 text-xs font-medium">
                                Full Access (Super Admin)
                            </span>
                        @else
                            <span class="bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 rounded-full px-3 py-1 text-xs font-medium">
                                {{ $item->permissions->count() }} Active Permissions
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <a href="{{ route('settings.edit', $item->id) }}" class="inline-flex items-center gap-2 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/20 font-medium py-2 px-4 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Configure Access
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection