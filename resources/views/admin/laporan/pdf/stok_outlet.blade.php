{{-- resources/views/admin/laporan/pdf/stok_outlet.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Outlet</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>

<h2>LAPORAN STOK OUTLET</h2>
<p><strong>Outlet:</strong> {{ $outlet->nama_outlet }}</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Bahan</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stok as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->bahan->nama_bahan }}</td>
            <td>{{ $item->stok }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p style="margin-top:20px;">
    Dicetak pada: {{ now()->format('d-m-Y') }}
</p>

</body>
</html>