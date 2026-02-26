@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white">User Management</h1>
        <p class="text-slate-400">Manage system users and access levels</p>
    </div>
    
    <div class="flex gap-3">
        @if (session('level') == 6 || session('level') == 5)
            <!-- Super Admin & Admin Actions -->
            <button onclick="toggleHistoryUser()" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors border border-blue-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                History
            </button>
            <button onclick="toggleTrashUser()" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors border border-red-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Trash
            </button>
        @endif

        @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('user.create'))
        <button onclick="openAddUserModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors shadow-lg shadow-indigo-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            Add New User
        </button>
        @endif
    </div>
</div>

<!-- Main User Table -->
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                <tr>
                    <th class="p-4 font-medium">No</th>
                    <th class="p-4 font-medium">User Profile</th>
                    <th class="p-4 font-medium">Email</th>
                    <th class="p-4 font-medium">Role / Level</th>
                    <th class="p-4 font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($users as $index => $user)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4 text-slate-500 dark:text-slate-400">{{ $index + 1 }}</td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg border border-indigo-500/30">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="font-medium text-slate-900 dark:text-white">{{ $user->name }}</div>
                        </div>
                    </td>
                    <td class="p-4 text-slate-600 dark:text-slate-300">{{ $user->email }}</td>
                    <td class="p-4">
                        @php
                            $levelColor = 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300'; // Default
                            if(strtolower($user->nama_level) == 'admin') $levelColor = 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/20';
                            elseif(strtolower($user->nama_level) == 'petugas') $levelColor = 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20';
                            elseif(strtolower($user->nama_level) == 'peminjam') $levelColor = 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20';
                            elseif(strtolower($user->nama_level) == 'super admin') $levelColor = 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20';
                        @endphp
                        <span class="{{ $levelColor }} border rounded-full px-3 py-1 text-xs font-medium">
                            {{ $user->nama_level }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Reset Password -->
                            @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('user.update'))
                            <a href="{{ url('/datauser/reset/' . $user->id) }}" onclick="return confirm('Reset password to default (12345678)?')" class="p-2 text-slate-400 hover:text-amber-500 dark:hover:text-amber-400 transition-colors" title="Reset Password">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </a>
                            
                            <button onclick="editUser({{ json_encode($user) }})" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            @endif
                            
                            @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('user.delete'))
                            <a href="{{ url('/datauser/delete/' . $user->id) }}" onclick="return confirm('Are you sure you want to delete this user?')" class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- History Container -->
<div id="historyContainer" class="hidden mt-8">
    <h2 class="text-xl font-bold text-blue-400 mb-4 flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Edit History (Revertable)
    </h2>
    <div class="bg-slate-800 rounded-xl shadow-lg border border-blue-500/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-900/50 text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="p-4">Editor</th>
                        <th class="p-4">Changes</th>
                        <th class="p-4">Date</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @if(isset($historyUsers) && count($historyUsers) > 0)
                        @foreach($historyUsers as $h)
                        <tr class="hover:bg-slate-700/50">
                            <td class="p-4 text-white">{{ $h->editor_name }}</td>
                            <td class="p-4 text-slate-300 text-sm">{{ $h->perubahan }}</td>
                            <td class="p-4 text-slate-400 text-xs">{{ $h->created_at }}</td>
                            <td class="p-4 text-center">
                                <a href="{{ url('/revert/' . $h->id) }}" onclick="return confirm('Revert changes?')" class="text-blue-400 hover:text-blue-300 text-sm font-medium">Revert</a>
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

<!-- Trash Table (Hidden by default) -->
<div id="trashContainer" class="mt-8 hidden">
    <h2 class="text-xl font-bold text-red-400 mb-4 flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        Trash Bin (Soft Deleted)
    </h2>
    <div class="bg-slate-800 rounded-xl shadow-lg border border-red-500/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-900/50 text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="p-4">User Name</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Deleted At</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @if(isset($deletedUsers) && count($deletedUsers) > 0)
                        @foreach($deletedUsers as $d)
                        <tr class="hover:bg-slate-700/50">
                            <td class="p-4 text-white">{{ $d->name }}</td>
                            <td class="p-4 text-slate-300">{{ $d->nama_level }}</td>
                            <td class="p-4 text-slate-400 text-xs">{{ $d->deleted_at }}</td>
                            <td class="p-4 text-center flex justify-center gap-3">
                                <a href="{{ url('/restore/user/' . $d->id) }}" class="text-emerald-400 hover:text-emerald-300 font-medium text-sm">Restore</a>
                                <a href="{{ url('/force-delete/user/' . $d->id) }}" onclick="return confirm('Delete permanently?')" class="text-red-400 hover:text-red-300 font-medium text-sm">Delete</a>
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

<!-- Add/Edit User Modal -->
<div id="userModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeUserModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-slate-700">
            
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold leading-6 text-slate-900 dark:text-white" id="modalTitle">Add New User</h3>
                <button type="button" onclick="closeUserModal()" class="text-slate-400 hover:text-slate-500 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="userForm" action="{{ url('/datauser/store') }}" method="POST">
                @csrf
                <div id="methodContainer"></div>

                <div class="px-6 py-6 space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-2">Full Name</label>
                        <input type="text" name="name" id="userName" required
                            class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-2">Email Address</label>
                        <input type="email" name="email" id="userEmail" required
                            class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-2">Password</label>
                        <input type="password" name="password" id="userPassword"
                            class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4"
                            placeholder="******">
                        <p class="mt-1 text-xs text-slate-500" id="passwordHint" style="display:none;">Leave blank to keep current password</p>
                    </div>

                    <!-- Level -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-400 mb-2">Role / Level</label>
                        <select name="level_id" id="userLevel" required
                            class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white py-2.5 px-4">
                            <option value="">Select Role</option>
                            @foreach($levels as $lvl)
                            <option value="{{ $lvl->id }}">{{ $lvl->nama_level }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-200 dark:border-slate-700">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors shadow-lg shadow-indigo-600/20">
                        Save User
                    </button>
                    <button type="button" onclick="closeUserModal()" class="bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-white font-medium py-2 px-4 rounded-lg transition-colors border border-slate-300 dark:border-transparent">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddUserModal() {
        document.getElementById('userModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Add New User';
        document.getElementById('userForm').action = "{{ url('/datauser/store') }}";
        document.getElementById('methodContainer').innerHTML = '';
        document.getElementById('userForm').reset();
        document.getElementById('passwordHint').style.display = 'none';
        document.getElementById('userPassword').required = true;
    }

    function editUser(user) {
        document.getElementById('userModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = 'Edit User';
        document.getElementById('userForm').action = "{{ url('/datauser/update') }}/" + user.id;
        
        document.getElementById('userName').value = user.name;
        document.getElementById('userEmail').value = user.email;
        document.getElementById('userLevel').value = user.level_id;
        
        // Password logic
        document.getElementById('userPassword').value = '';
        document.getElementById('userPassword').required = false;
        document.getElementById('passwordHint').style.display = 'block';
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    function toggleHistoryUser() {
        document.getElementById('historyContainer').classList.toggle('hidden');
        document.getElementById('trashContainer').classList.add('hidden');
    }

    function toggleTrashUser() {
        document.getElementById('trashContainer').classList.toggle('hidden');
        document.getElementById('historyContainer').classList.add('hidden');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeUserModal();
        }
    });
</script>
@endsection
