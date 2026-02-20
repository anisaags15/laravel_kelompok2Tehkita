<!DOCTYPE html>
<html>
<head>
    <title>Laporan Lengkap</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>LAPORAN LENGKAP</h2>

    <h3>Stok Masuk</h3>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Bahan</th><th>Jumlah</th><th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokMasuk as $i => $sm)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $sm->bahan->nama_bahan ?? '-' }}</td>
                <td>{{ $sm->jumlah }}</td>
                <td>{{ $sm->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Distribusi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Outlet</th><th>Bahan</th><th>Jumlah</th><th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($distribusi as $i => $d)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $d->outlet->nama_outlet ?? '-' }}</td>
                <td>{{ $d->bahan->nama_bahan ?? '-' }}</td>
                <td>{{ $d->jumlah }}</td>
                <td>{{ $d->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Pemakaian</h3>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Outlet</th><th>Bahan</th><th>Jumlah</th><th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemakaian as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->outlet->nama_outlet ?? '-' }}</td>
                <td>{{ $p->bahan->nama_bahan ?? '-' }}</td>
                <td>{{ $p->jumlah }}</td>
                <td>{{ $p->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>