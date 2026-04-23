@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ __('Book Data') }}</h1>
        <p class="text-slate-500 dark:text-slate-400">{{ __('Manage your library collection') }}</p>
    </div>
    
    <div class="flex gap-3">
        @if (session('level') == 6 || session('level') == 5)
            <!-- Super Admin & Admin Actions -->
            <button onclick="toggleHistoryBook()" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-500 dark:text-blue-400 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors border border-blue-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ __('History') }}
            </button>
            <button onclick="toggleTrashBook()" class="bg-red-500/10 hover:bg-red-500/20 text-red-500 dark:text-red-400 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors border border-red-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                {{ __('Trash') }}
            </button>
        @endif

        @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('buku.create'))
        <button onclick="openImportModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors shadow-lg shadow-emerald-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
            </svg>
            {{ __('Import Excel') }}
        </button>
        <button onclick="openAddBookModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors shadow-lg shadow-indigo-600/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('Add New Book') }}
        </button>
        @endif
    </div>
</div>

<!-- Table Toolbar -->
<div class="mb-4 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 mb-1">{{ __('Search') }}</label>
            <input id="bookSearchInput" type="text" class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent rounded-lg px-3 py-2 text-sm text-slate-900 dark:text-white" placeholder="{{ __('Search by title, author, publisher...') }}">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">{{ __('Category') }}</label>
            <select id="bookCategoryFilter" class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent rounded-lg px-3 py-2 text-sm text-slate-900 dark:text-white">
                <option value="">{{ __('All Categories') }}</option>
                @foreach($kategori as $k)
                <option value="{{ strtolower($k->nama_kategori) }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">{{ __('Stock Status') }}</label>
            <select id="bookStockFilter" class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent rounded-lg px-3 py-2 text-sm text-slate-900 dark:text-white">
                <option value="">{{ __('All') }}</option>
                <option value="in">{{ __('In Stock') }}</option>
                <option value="out">{{ __('Out of Stock') }}</option>
            </select>
        </div>
    </div>
</div>

<!-- Glassy Table Container -->
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
    <div class="overflow-x-auto">
        <table class="js-smart-table w-full text-left border-collapse" data-filter-fields="category,stock">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                <tr>
                    <th class="p-4 font-medium">{{ __('No') }}</th>
                    <th class="p-4 font-medium">{{ __('Book Title') }}</th>
                    <th class="p-4 font-medium">{{ __('Category') }}</th>
                    <th class="p-4 font-medium">{{ __('Author') }} & {{ __('Publisher') }}</th>
                    <th class="p-4 font-medium">{{ __('Stock') }}</th>
                    <th class="p-4 font-medium text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($buku as $index => $item)
                <tr class="book-row hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    data-title="{{ strtolower($item->judul) }}"
                    data-author="{{ strtolower($item->nama_penulis) }}"
                    data-publisher="{{ strtolower($item->nama_penerbit) }}"
                    data-category="{{ strtolower($item->nama_kategori ?? '') }}"
                    data-stock="{{ (int) $item->stok > 0 ? 'in' : 'out' }}">
                    <td class="p-4 text-slate-500 dark:text-slate-400">{{ $index + 1 }}</td>
                    <td class="p-4">
                        <div class="flex items-center gap-4">
                            @if($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" class="w-12 h-16 rounded-md object-cover shadow-sm">
                            @else
                                <div class="w-12 h-16 rounded-md bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-400 dark:text-slate-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-slate-800 dark:text-white">{{ $item->judul }}</div>
                                <div class="text-xs text-slate-500">{{ $item->tahun }}</div>
                                @if($item->file_buku)
                                    <a href="{{ asset('storage/' . $item->file_buku) }}" target="_blank" class="inline-flex items-center gap-1 mt-1 text-xs text-indigo-500 dark:text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        {{ __('View E-Book') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-2 py-1 rounded text-xs border border-slate-200 dark:border-slate-600">
                            {{ $item->nama_kategori ?? '-' }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="text-sm text-slate-600 dark:text-slate-300">{{ $item->nama_penulis }}</div>
                        <div class="text-xs text-slate-500">{{ $item->nama_penerbit }}</div>
                    </td>
                    <td class="p-4">
                        @if($item->stok > 0)
                            <span class="bg-emerald-500/10 text-emerald-500 dark:text-emerald-400 border border-emerald-500/20 rounded-full px-3 py-1 text-xs font-medium">
                                {{ $item->stok }} {{ __('Available') }}
                            </span>
                        @else
                            <span class="bg-red-500/10 text-red-500 dark:text-red-400 border border-red-500/20 rounded-full px-3 py-1 text-xs font-medium">
                                {{ __('Out of Stock') }}
                            </span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex items-center justify-center gap-2">
                            @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('buku.update'))
                            <button onclick="editBook({{ json_encode($item) }})" class="p-2 text-slate-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors" title="{{ __('Edit') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            @endif
                            
                            @if (app(\App\Helpers\PermissionHelper::class)->hasPermission('buku.delete'))
                            <a href="{{ url('/databuku/delete/' . $item->id) }}" onclick="return confirm('{{ __('Are you sure?') }}')" class="p-2 text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors" title="{{ __('Delete') }}">
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
                    <td colspan="6" class="p-8 text-center text-slate-500">
                        {{ __('No books found.') }}
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
        {{ __('Edit History (Revertable)') }}
    </h2>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-blue-500/20 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="p-4">{{ __('Editor') }}</th>
                        <th class="p-4">{{ __('Changes') }}</th>
                        <th class="p-4">{{ __('Date') }}</th>
                        <th class="p-4 text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @if(isset($historyBooks) && count($historyBooks) > 0)
                        @foreach($historyBooks as $h)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="p-4 text-slate-800 dark:text-white">{{ $h->editor_name }}</td>
                            <td class="p-4 text-slate-600 dark:text-slate-300 text-sm">{{ $h->perubahan }}</td>
                            <td class="p-4 text-slate-500 dark:text-slate-400 text-xs">{{ $h->created_at }}</td>
                            <td class="p-4 text-center">
                                <a href="{{ url('/revert/' . $h->id) }}" onclick="return confirm('{{ __('Revert changes?') }}')" class="text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 text-sm font-medium">{{ __('Revert') }}</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4" class="p-4 text-center text-slate-500">{{ __('No history found.') }}</td></tr>
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
        {{ __('Trash Bin (Soft Deleted)') }}
    </h2>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-red-500/20 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="p-4">{{ __('Title') }}</th>
                        <th class="p-4">{{ __('Deleted At') }}</th>
                        <th class="p-4 text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @if(isset($deletedBooks) && count($deletedBooks) > 0)
                        @foreach($deletedBooks as $d)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="p-4 text-slate-800 dark:text-white">{{ $d->judul }}</td>
                            <td class="p-4 text-slate-500 dark:text-slate-400 text-xs">{{ $d->deleted_at }}</td>
                            <td class="p-4 text-center flex justify-center gap-3">
                                <a href="{{ url('/restore/book/' . $d->id) }}" class="text-emerald-500 dark:text-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-300 font-medium text-sm">{{ __('Restore') }}</a>
                                <a href="{{ url('/force-delete/book/' . $d->id) }}" onclick="return confirm('{{ __('Delete permanently?') }}')" class="text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 font-medium text-sm">{{ __('Delete') }}</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="3" class="p-4 text-center text-slate-500">{{ __('No deleted items.') }}</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Book Modal -->
<div id="bookModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeBookModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200 dark:border-slate-700">
            
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold leading-6 text-slate-800 dark:text-white" id="modalTitle">Add New Book</h3>
                <button type="button" onclick="closeBookModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="bookForm" action="{{ url('/databuku/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="bookId">
                <div id="methodContainer"></div>

                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Book Title</label>
                            <input type="text" name="judul" id="judul" required
                                class="w-full bg-slate-100 dark:bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4 transition-colors">
                        </div>

                        <!-- Author -->
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Author</label>
                            <select name="penulis_id" id="penulis_id" required
                                class="w-full bg-slate-100 dark:bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white py-2.5 px-4 transition-colors">
                                <option value="">Select Author</option>
                                @foreach($penulis as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_penulis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Publisher -->
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Publisher</label>
                            <select name="penerbit_id" id="penerbit_id" required
                                class="w-full bg-slate-100 dark:bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white py-2.5 px-4 transition-colors">
                                <option value="">Select Publisher</option>
                                @foreach($penerbit as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_penerbit }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Year & Stock -->
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Year</label>
                            <input type="number" name="tahun" id="tahun" required
                                class="w-full bg-slate-100 dark:bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Stock</label>
                            <input type="number" name="stok" id="stok" required
                                class="w-full bg-slate-100 dark:bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 py-2.5 px-4 transition-colors">
                        </div>

                        <!-- Category -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">{{ __('Category') }}</label>
                            <select name="kategori_id" id="kategori_id" required
                                class="w-full bg-slate-100 dark:bg-slate-700 border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-lg text-slate-900 dark:text-white py-2.5 px-4 transition-colors">
                                <option value="">Select Category</option>
                                @foreach($kategori as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cover Image (Drag & Drop) -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">{{ __('Cover') }}</label>
                            <div id="drop-zone-foto" class="relative w-full h-48 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-800 hover:border-indigo-500 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all flex flex-col items-center justify-center cursor-pointer group overflow-hidden">
                                <input type="file" name="foto" id="foto" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="handleFileSelect(this, 'preview-foto', 'placeholder-foto')">
                                
                                <div id="placeholder-foto" class="text-center pointer-events-none transition-opacity duration-300">
                                    <svg class="w-10 h-10 text-slate-400 dark:text-slate-500 mx-auto mb-2 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Drag & Drop or Click to Upload</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">JPEG, PNG, JPG (Max 2MB)</p>
                                </div>

                                <img id="preview-foto" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                                
                                <!-- Remove Button (Visible when image is set) -->
                                <button type="button" id="remove-foto" onclick="removeImage('foto', 'preview-foto', 'placeholder-foto')" class="absolute top-2 right-2 bg-red-600/80 hover:bg-red-600 text-white rounded-full p-1 z-20 hidden transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- E-Book PDF (Drag & Drop) -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">E-Book (PDF)</label>
                            <div class="relative w-full h-32 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-800 hover:border-indigo-500 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all flex flex-col items-center justify-center cursor-pointer group">
                                <input type="file" name="file_buku" id="file_buku" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="handlePdfSelect(this, 'filename-pdf')">
                                
                                <div class="text-center pointer-events-none">
                                    <svg class="w-10 h-10 text-slate-400 dark:text-slate-500 mx-auto mb-2 group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <p id="filename-pdf" class="text-sm text-slate-500 dark:text-slate-400 font-medium">Drag PDF here or Click</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Format: .pdf only</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-200 dark:border-slate-700 transition-colors">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors shadow-lg shadow-indigo-600/20">
                        Save Book
                    </button>
                    <button type="button" onclick="closeBookModal()" class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeImportModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold leading-6 text-slate-800 dark:text-white">Import Books from Excel</h3>
                <button type="button" onclick="closeImportModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Upload file Excel (.xlsx, .xls, .csv) dengan format kolom: 
                        <span class="font-mono bg-slate-100 dark:bg-slate-700 px-1 rounded">Judul, Penulis, Penerbit, Tahun, Kategori, Stok</span>.
                    </p>
                    <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-slate-300">
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-200 dark:border-slate-700">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg">Upload & Import</button>
                    <button type="button" onclick="closeImportModal()" class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white font-medium py-2 px-4 rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const bookUiText = {
        addTitle: @json(__('Add New Book')),
        editTitle: @json(__('Edit Book')),
        pdfPlaceholder: @json(__('Drag PDF here or Click')),
    };

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('bookSearchInput');
        const categoryFilter = document.getElementById('bookCategoryFilter');
        const stockFilter = document.getElementById('bookStockFilter');
        const rows = Array.from(document.querySelectorAll('.book-row'));

        function applyBookFilters() {
            const query = (searchInput?.value || '').toLowerCase().trim();
            const category = (categoryFilter?.value || '').toLowerCase();
            const stock = (stockFilter?.value || '').toLowerCase();

            rows.forEach((row) => {
                const title = row.dataset.title || '';
                const author = row.dataset.author || '';
                const publisher = row.dataset.publisher || '';
                const rowCategory = row.dataset.category || '';
                const rowStock = row.dataset.stock || '';

                const textMatch = !query || title.includes(query) || author.includes(query) || publisher.includes(query);
                const categoryMatch = !category || rowCategory === category;
                const stockMatch = !stock || rowStock === stock;

                row.style.display = (textMatch && categoryMatch && stockMatch) ? '' : 'none';
            });
        }

        searchInput?.addEventListener('input', applyBookFilters);
        categoryFilter?.addEventListener('change', applyBookFilters);
        stockFilter?.addEventListener('change', applyBookFilters);
    });

    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
    }
    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
    }

    function handleFileSelect(input, previewId, placeholderId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
                document.getElementById(previewId).classList.remove('hidden');
                document.getElementById(placeholderId).classList.add('opacity-0');
                
                // Show remove button if exists
                var removeBtn = document.getElementById('remove-foto');
                if(removeBtn) removeBtn.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handlePdfSelect(input, labelId) {
        if (input.files && input.files[0]) {
            document.getElementById(labelId).innerText = input.files[0].name;
            document.getElementById(labelId).classList.add('text-indigo-400');
        }
    }

    function removeImage(inputId, previewId, placeholderId) {
        document.getElementById(inputId).value = '';
        document.getElementById(previewId).src = '';
        document.getElementById(previewId).classList.add('hidden');
        document.getElementById(placeholderId).classList.remove('opacity-0');
        document.getElementById('remove-foto').classList.add('hidden');
    }

    function openAddBookModal() {
        document.getElementById('bookModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = bookUiText.addTitle;
        document.getElementById('bookForm').action = "{{ url('/databuku/store') }}";
        document.getElementById('methodContainer').innerHTML = '';
        document.getElementById('bookForm').reset();
        
        // Reset Drag Drop UI
        removeImage('foto', 'preview-foto', 'placeholder-foto');
        document.getElementById('filename-pdf').innerText = bookUiText.pdfPlaceholder;
        document.getElementById('filename-pdf').classList.remove('text-indigo-400');
    }

    function editBook(book) {
        document.getElementById('bookModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = bookUiText.editTitle;
        document.getElementById('bookForm').action = "{{ url('/databuku/update') }}/" + book.id;
        document.getElementById('bookId').value = book.id;
        
        document.getElementById('judul').value = book.judul;
        document.getElementById('penulis_id').value = book.penulis_id;
        document.getElementById('penerbit_id').value = book.penerbit_id;
        document.getElementById('tahun').value = book.tahun;
        document.getElementById('stok').value = book.stok;
        document.getElementById('kategori_id').value = book.kategori_id;
        
        // Handle Image Preview if Exists
        if (book.foto) {
            document.getElementById('preview-foto').src = "{{ asset('storage') }}/" + book.foto;
            document.getElementById('preview-foto').classList.remove('hidden');
            document.getElementById('placeholder-foto').classList.add('opacity-0');
            document.getElementById('remove-foto').classList.remove('hidden');
        } else {
            removeImage('foto', 'preview-foto', 'placeholder-foto');
        }

        // Handle PDF Label
        if (book.file_buku) {
            document.getElementById('filename-pdf').innerText = "Current: " + book.file_buku.split('/').pop();
            document.getElementById('filename-pdf').classList.add('text-indigo-400');
        } else {
            document.getElementById('filename-pdf').innerText = bookUiText.pdfPlaceholder;
            document.getElementById('filename-pdf').classList.remove('text-indigo-400');
        }
    }

    function closeBookModal() {
        document.getElementById('bookModal').classList.add('hidden');
    }

    function toggleHistoryBook() {
        document.getElementById('historyContainer').classList.toggle('hidden');
        document.getElementById('trashContainer').classList.add('hidden');
    }

    function toggleTrashBook() {
        document.getElementById('trashContainer').classList.toggle('hidden');
        document.getElementById('historyContainer').classList.add('hidden');
    }
</script>
@endsection
