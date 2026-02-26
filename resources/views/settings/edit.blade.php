@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white">Edit Permissions</h1>
        <p class="text-slate-400">
            Configure access rights for role: 
            <span class="text-indigo-400 font-bold">{{ $level->nama_level }}</span>
        </p>
    </div>
    <a href="{{ route('settings.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Roles
    </a>
</div>

<div class="bg-slate-800 rounded-xl shadow-lg border border-slate-700 p-6">
    <form action="{{ route('settings.update', $level->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($permissions as $module => $perms)
            <div class="bg-slate-900/50 rounded-lg border border-slate-700 overflow-hidden">
                <!-- Module Header -->
                <div class="px-4 py-3 bg-slate-700/50 border-b border-slate-700 flex items-center justify-between">
                    <h3 class="font-bold text-white uppercase tracking-wider text-sm">
                        {{ strtoupper(str_replace('-', ' ', $module)) }}
                    </h3>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" id="select_all_{{ $module }}" onclick="toggleSelectAll('{{ $module }}', this)" 
                            class="w-4 h-4 rounded border-slate-600 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-800 bg-slate-700 transition-colors">
                        <span class="text-xs text-indigo-400 font-medium group-hover:text-indigo-300 transition-colors">Select All</span>
                    </label>
                </div>

                <!-- Permission Items -->
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($perms as $perm)
                    <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-slate-800/50 transition-colors">
                        <div class="flex h-5 items-center">
                            <input class="perm-item-{{ $module }} w-4 h-4 rounded border-slate-600 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-800 bg-slate-700 transition-colors" 
                                   type="checkbox" 
                                   name="permissions[]" 
                                   value="{{ $perm->id }}" 
                                   id="perm_{{ $perm->id }}"
                                   {{ $level->permissions->contains('id', $perm->id) ? 'checked' : '' }}>
                        </div>
                        <label for="perm_{{ $perm->id }}" class="text-sm text-slate-300 cursor-pointer select-none leading-5">
                            {{ $perm->description }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8 pt-6 border-t border-slate-700 flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-indigo-600/20 transition-all transform hover:scale-105">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    function toggleSelectAll(moduleName, sourceCheckbox) {
        const checkboxes = document.querySelectorAll('.perm-item-' + moduleName);
        checkboxes.forEach(checkbox => {
            checkbox.checked = sourceCheckbox.checked;
        });
    }
</script>
@endsection