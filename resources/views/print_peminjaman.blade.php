<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body onload="window.print()">

<h2>Laporan Peminjaman</h2>

<table class="print-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Judul Buku</th>
            <th>Kategori</th>
            <th>Jenis Transaksi</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($laporan as $l)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $l->nama_peminjam }}</td>
            <td>{{ $l->judul_buku }}</td>
            <td>{{ $l->kategori ?? '-' }}</td>
            <td>{{ ($l->transaction_type ?? 'pinjam') === 'baca_di_tempat' ? 'Baca di Tempat' : 'Pinjam (Bawa Pulang)' }}</td>
            <td>{{ $l->tanggal_pinjam }}</td>
            <td>{{ $l->tanggal_kembali ?? '-' }}</td>
            <td>
                @if(strtolower((string) $l->status) == 'dipinjam')
                   Dipinjam
                @elseif(strtolower((string) $l->status) == 'dikembalikan')
                    Dikembalikan
                @elseif(strtolower((string) $l->status) == 'baca_di_tempat')
                    Baca di Tempat
                @else
                    {{ $l->status }}
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align:center;">Tidak ada data peminjaman ditemukan.</td>
        </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
