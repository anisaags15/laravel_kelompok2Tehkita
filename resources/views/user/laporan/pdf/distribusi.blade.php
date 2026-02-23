<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width:100%; border-collapse: collapse; margin-top:15px; }
        th, td { border:1px solid #000; padding:6px; text-align:center; }
    </style>
</head>
<body>

<h3 style="text-align:center;">LAPORAN DISTRIBUSI OUTLET</h3>
<p>Outlet: {{ $outlet->nama_outlet }}</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Bahan</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($distribusi as $key => $item)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $item->tanggal }}</td>
            <td>{{ $item->bahan->nama_bahan }}</td>
            <td>{{ $item->jumlah }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>