<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Buku Masuk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body onload="window.print()">
    <h3>LAPORAN BUKU MASUK</h3>
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th>Tanggal Masuk</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $l)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $l->judul }}</td>
                <td>{{ $l->nama_penulis }}</td>
                <td>{{ $l->tanggal_masuk }}</td>
                <td>{{ $l->jumlah }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
