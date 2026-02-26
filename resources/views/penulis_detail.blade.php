@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row items-center gap-6">
    <div class="w-24 h-24 bg-indigo-500 rounded-full flex items-center justify-center text-4xl font-bold text-white shadow-lg">
        {{ substr($penulis->nama_penulis, 0, 1) }}
    </div>
    <div>
        <h1 class="text-3xl font-bold text-white">{{ $penulis->nama_penulis }}</h1>
        <p class="text-slate-400">Author Profile & Publications</p>
        <div class="mt-2 flex gap-4 text-sm">
            <div class="bg-slate-800 px-3 py-1 rounded-full border border-slate-700 text-slate-300">
                📚 {{ count($buku) }} Books Published
            </div>
        </div>
    </div>
</div>

<h2 class="text-xl font-bold text-white mb-6 border-l-4 border-indigo-500 pl-4">Books by {{ $penulis->nama_penulis }}</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($buku as $b)
    <div class="bg-slate-800 rounded-xl overflow-hidden shadow-lg border border-slate-700 hover:border-indigo-500/50 transition-all duration-300 flex flex-col h-full group">
        <!-- Image -->
        <div class="aspect-[2/3] w-full bg-slate-900 relative overflow-hidden">
            @if($b->foto)
                <img src="{{ asset('storage/'.$b->foto) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-600 bg-slate-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif
        </div>

        <!-- Content -->
        <div class="p-4 flex-1 flex flex-col">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    {{ $b->nama_kategori ?? 'Uncategorized' }}
                </span>
                @if($b->stok > 0)
                    <span class="text-[10px] font-bold text-emerald-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Available
                    </span>
                @else
                    <span class="text-[10px] font-bold text-red-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Out of Stock
                    </span>
                @endif
            </div>

            <h3 class="text-lg font-bold text-white mb-1 line-clamp-2 group-hover:text-indigo-400 transition-colors">{{ $b->judul }}</h3>
            <p class="text-xs text-slate-400 mb-4">{{ $b->nama_penerbit }}</p>
            
            <div class="mt-auto pt-4 border-t border-slate-700/50 flex justify-between items-center">
                @if($b->stok > 0)
                    <form action="{{ url('/peminjaman/pinjam/' . $b->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-4 rounded-lg transition-all shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Borrow Now
                        </button>
                    </form>
                @else
                    <button disabled class="w-full bg-slate-700 text-slate-500 text-sm font-bold py-2 px-4 rounded-lg cursor-not-allowed">
                        Unavailable
                    </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full p-8 text-center text-slate-500 bg-slate-800 rounded-xl border border-slate-700">
        No books found for this author.
    </div>
    @endforelse
</div>
@endsection
