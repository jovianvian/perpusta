@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Library Collection</h1>
    <p class="text-slate-500 dark:text-slate-400">Explore and borrow books</p>
</div>

<!-- Search & Filter -->
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 mb-8">
    <div class="flex flex-col md:flex-row gap-4">
        <!-- Search Input -->
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" id="searchInput" placeholder="Search by title or author..." 
                class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent rounded-lg pl-10 pr-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 transition-all">
        </div>
        
        <!-- Category Filter -->
        <div class="w-full md:w-64">
            <select id="filterKategori" class="w-full bg-slate-50 dark:bg-slate-700 border-slate-300 dark:border-transparent rounded-lg text-slate-900 dark:text-white py-3 px-4 focus:ring-2 focus:ring-indigo-500 transition-all">
                <option value="">All Categories</option>
                @foreach($kategoriList as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<!-- Book Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="bookContainer">
    @forelse($buku as $index => $b)
    <div class="book-item group" 
         data-judul="{{ strtolower($b->judul) }}" 
         data-penulis="{{ strtolower($b->nama_penulis) }}" 
         data-kategori="{{ $b->kategori_id }}">
        
        <div class="bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow-lg border border-slate-200 dark:border-slate-700 hover:border-indigo-500/50 transition-all duration-300 flex flex-col h-full relative">
            
            <!-- Trending Badge -->
            @if($index < 3 && $b->peminjaman_count > 0 && !request('kategori_id'))
            <div class="absolute top-0 left-0 z-10">
                <div class="bg-orange-500 text-white text-[10px] font-bold px-3 py-1 rounded-br-lg shadow-lg flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                    TRENDING #{{ $index + 1 }}
                </div>
            </div>
            @endif

            <!-- Image -->
            <div class="aspect-[2/3] w-full bg-slate-200 dark:bg-slate-900 relative overflow-hidden">
                @if($b->foto)
                    <img src="{{ asset('storage/'.$b->foto) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400 dark:text-slate-600 bg-slate-200 dark:bg-slate-800">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                
                <!-- Stock Badge Overlay -->
                <div class="absolute top-3 right-3">
                    @if($b->stok > 0)
                        <span class="bg-emerald-500/90 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg backdrop-blur-sm">
                            {{ $b->stok }} Available
                        </span>
                    @else
                        <span class="bg-red-500/90 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg backdrop-blur-sm">
                            Out of Stock
                        </span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="p-5 flex flex-col flex-1">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1 line-clamp-1" title="{{ $b->judul }}">{{ $b->judul }}</h3>
                <a href="{{ url('/penulis/' . $b->penulis_id) }}" class="text-sm text-slate-500 dark:text-slate-400 mb-2 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline transition-colors block" title="View Author Profile">
                    {{ $b->nama_penulis }}
                </a>
                <div class="mb-4">
                    <span class="text-xs bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-600">
                        {{ $b->nama_kategori ?? 'Uncategorized' }}
                    </span>
                </div>
                
                <div class="mt-auto flex flex-col gap-2">
                    @if($b->file_buku)
                        <a href="{{ asset('storage/'.$b->file_buku) }}" target="_blank" class="w-full bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-indigo-600 dark:text-indigo-400 font-medium py-2 rounded-lg transition-colors border border-slate-200 dark:border-slate-600 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Read E-Book
                        </a>
                    @endif

                    @if($b->stok > 0)
                        <form action="{{ url('/peminjaman/pinjam/'.$b->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-lg transition-colors shadow-lg shadow-indigo-600/20">
                                Borrow Physical Book
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 font-medium py-2 rounded-lg cursor-not-allowed border border-slate-200 dark:border-slate-600">
                            Unavailable
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        <p class="text-xl text-slate-500 dark:text-slate-400">No books found in the collection.</p>
    </div>
    @endforelse
</div>

<!-- Not Found Message (JS) -->
<div id="notFound" class="hidden text-center py-12">
    <svg class="w-16 h-16 text-slate-400 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
    <p class="text-xl text-slate-500 dark:text-slate-400">No books match your search.</p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterKategori = document.getElementById('filterKategori');
    const books = document.querySelectorAll('.book-item');
    const notFound = document.getElementById('notFound');

    function filterBooks() {
        const keyword = searchInput.value.toLowerCase();
        const kategori = filterKategori.value;
        let visibleCount = 0;

        books.forEach(book => {
            const judul = book.getAttribute('data-judul');
            const penulis = book.getAttribute('data-penulis');
            const bookKategori = book.getAttribute('data-kategori');

            const cocokKeyword = judul.includes(keyword) || penulis.includes(keyword);
            const cocokKategori = kategori === '' || bookKategori === kategori;

            if (cocokKeyword && cocokKategori) {
                book.classList.remove('hidden');
                visibleCount++;
            } else {
                book.classList.add('hidden');
            }
        });

        if (visibleCount === 0) {
            notFound.classList.remove('hidden');
        } else {
            notFound.classList.add('hidden');
        }
    }

    searchInput.addEventListener('input', filterBooks);
    filterKategori.addEventListener('change', filterBooks);
});
</script>
@endsection