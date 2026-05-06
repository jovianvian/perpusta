@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ $activeTab }}' }">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ __('Master Data') }}</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">{{ __('Manage authors, categories, and publishers') }}</p>
        </div>
        
        <!-- Tab Buttons (Mobile) -->
        <div class="sm:hidden">
            <select x-model="activeTab" class="block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                <option value="penulis">{{ __('Authors') }}</option>
                <option value="kategori">{{ __('Categories') }}</option>
                <option value="penerbit">{{ __('Publishers') }}</option>
            </select>
        </div>

        <!-- Add Button (Dynamic based on Tab) -->
        <div>
            <button x-show="activeTab === 'penulis'" onclick="openModal('addPenulisModal')" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-indigo-600/20">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Add Author') }}
            </button>
            <button x-show="activeTab === 'kategori'" onclick="openModal('addKategoriModal')" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-indigo-600/20" style="display: none;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Add Category') }}
            </button>
            <button x-show="activeTab === 'penerbit'" onclick="openModal('addPenerbitModal')" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-indigo-600/20" style="display: none;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Add Publisher') }}
            </button>
        </div>
    </div>

    <!-- Tabs (Desktop) -->
    <div class="hidden sm:block border-b border-slate-200 dark:border-slate-700">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'penulis'" :class="activeTab === 'penulis' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                {{ __('Authors') }}
            </button>
            <button @click="activeTab = 'kategori'" :class="activeTab === 'kategori' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                {{ __('Categories') }}
            </button>
            <button @click="activeTab = 'penerbit'" :class="activeTab === 'penerbit' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:border-slate-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                {{ __('Publishers') }}
            </button>
        </nav>
    </div>

    <!-- Content Area -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        
        <!-- TAB: PENULIS -->
        <div x-show="activeTab === 'penulis'" class="overflow-x-auto">
            <table class="js-smart-table w-full text-left text-sm text-slate-600 dark:text-slate-300" data-smart-mode="manual">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-xs uppercase font-semibold text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">{{ __('Author Name') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($penulis as $index => $item)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $item->nama_penulis }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditPenulisModal({{ $item->id }}, '{{ addslashes($item->nama_penulis) }}')" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="{{ __('Edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <a href="{{ url('/master/penulis/delete/'.$item->id) }}" onclick="return confirm('{{ __('Are you sure want to delete this author?') }}')" class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="{{ __('Delete') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">{{ __('No data available') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TAB: KATEGORI -->
        <div x-show="activeTab === 'kategori'" class="overflow-x-auto" style="display: none;">
            <table class="js-smart-table w-full text-left text-sm text-slate-600 dark:text-slate-300" data-smart-mode="manual">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-xs uppercase font-semibold text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">{{ __('Category Name') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($kategori as $index => $item)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $item->nama_kategori }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditKategoriModal({{ $item->id }}, '{{ addslashes($item->nama_kategori) }}')" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="{{ __('Edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <a href="{{ url('/master/kategori/delete/'.$item->id) }}" onclick="return confirm('{{ __('Are you sure want to delete this category?') }}')" class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="{{ __('Delete') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">{{ __('No data available') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TAB: PENERBIT -->
        <div x-show="activeTab === 'penerbit'" class="overflow-x-auto" style="display: none;">
            <table class="js-smart-table w-full text-left text-sm text-slate-600 dark:text-slate-300" data-smart-mode="manual">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-xs uppercase font-semibold text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">{{ __('Publisher Name') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($penerbit as $index => $item)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $item->nama_penerbit }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditPenerbitModal({{ $item->id }}, '{{ addslashes($item->nama_penerbit) }}')" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="{{ __('Edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <a href="{{ url('/master/penerbit/delete/'.$item->id) }}" onclick="return confirm('{{ __('Are you sure want to delete this publisher?') }}')" class="p-2 text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="{{ __('Delete') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">{{ __('No data available') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODALS ================= -->

<!-- ADD AUTHOR MODAL -->
<div id="addPenulisModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true" onclick="closeModal('addPenulisModal')"></div>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700">
            <form action="{{ url('/master/penulis') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white">{{ __('Add New Author') }}</h3>
                    <div class="mt-4">
                        <label for="nama_penulis" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Author Name') }}</label>
                        <input type="text" name="nama_penulis" required class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Save') }}</button>
                    <button type="button" onclick="closeModal('addPenulisModal')" class="w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ADD CATEGORY MODAL -->
<div id="addKategoriModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true" onclick="closeModal('addKategoriModal')"></div>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700">
            <form action="{{ url('/master/kategori') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white">{{ __('Add New Category') }}</h3>
                    <div class="mt-4">
                        <label for="nama_kategori" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Category Name') }}</label>
                        <input type="text" name="nama_kategori" required class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Save') }}</button>
                    <button type="button" onclick="closeModal('addKategoriModal')" class="w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ADD PUBLISHER MODAL -->
<div id="addPenerbitModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true" onclick="closeModal('addPenerbitModal')"></div>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700">
            <form action="{{ url('/master/penerbit') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white">{{ __('Add New Publisher') }}</h3>
                    <div class="mt-4">
                        <label for="nama_penerbit" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Publisher Name') }}</label>
                        <input type="text" name="nama_penerbit" required class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Save') }}</button>
                    <button type="button" onclick="closeModal('addPenerbitModal')" class="w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT AUTHOR MODAL -->
<div id="editPenulisModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true" onclick="closeModal('editPenulisModal')"></div>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700">
            <form id="editPenulisForm" action="" method="POST">
                @csrf
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white">{{ __('Edit Author') }}</h3>
                    <div class="mt-4">
                        <label for="edit_nama_penulis" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Author Name') }}</label>
                        <input type="text" name="nama_penulis" id="edit_nama_penulis" required class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Update') }}</button>
                    <button type="button" onclick="closeModal('editPenulisModal')" class="w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT CATEGORY MODAL -->
<div id="editKategoriModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true" onclick="closeModal('editKategoriModal')"></div>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700">
            <form id="editKategoriForm" action="" method="POST">
                @csrf
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white">{{ __('Edit Category') }}</h3>
                    <div class="mt-4">
                        <label for="edit_nama_kategori" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Category Name') }}</label>
                        <input type="text" name="nama_kategori" id="edit_nama_kategori" required class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Update') }}</button>
                    <button type="button" onclick="closeModal('editKategoriModal')" class="w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT PUBLISHER MODAL -->
<div id="editPenerbitModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true" onclick="closeModal('editPenerbitModal')"></div>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700">
            <form id="editPenerbitForm" action="" method="POST">
                @csrf
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white">{{ __('Edit Publisher') }}</h3>
                    <div class="mt-4">
                        <label for="edit_nama_penerbit" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Publisher Name') }}</label>
                        <input type="text" name="nama_penerbit" id="edit_nama_penerbit" required class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Update') }}</button>
                    <button type="button" onclick="closeModal('editPenerbitModal')" class="w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none sm:w-auto sm:text-sm">{{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="//unpkg.com/alpinejs" defer></script>
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openEditPenulisModal(id, name) {
        document.getElementById('edit_nama_penulis').value = name;
        document.getElementById('editPenulisForm').action = "{{ url('/master/penulis') }}/" + id;
        openModal('editPenulisModal');
    }

    function openEditKategoriModal(id, name) {
        document.getElementById('edit_nama_kategori').value = name;
        document.getElementById('editKategoriForm').action = "{{ url('/master/kategori') }}/" + id;
        openModal('editKategoriModal');
    }

    function openEditPenerbitModal(id, name) {
        document.getElementById('edit_nama_penerbit').value = name;
        document.getElementById('editPenerbitForm').action = "{{ url('/master/penerbit') }}/" + id;
        openModal('editPenerbitModal');
    }
</script>
@endsection
