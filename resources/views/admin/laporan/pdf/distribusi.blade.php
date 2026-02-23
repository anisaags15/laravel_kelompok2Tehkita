{{-- resources/views/admin/laporan/pdf/distribusi.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Distribusi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>

<h2>LAPORAN DISTRIBUSI</h2>

<table>
    <tr><td><strong>Tanggal</strong></td><td>{{ $distribusi->tanggal }}</td></tr>
    <tr><td><strong>Outlet</strong></td><td>{{ $distribusi->outlet->nama_outlet }}</td></tr>
    <tr><td><strong>Bahan</strong></td><td>{{ $distribusi->bahan->nama_bahan }}</td></tr>
    <tr><td><strong>Jumlah</strong></td><td>{{ $distribusi->jumlah }}</td></tr>
    <tr><td><strong>Status</strong></td><td>{{ $distribusi->status }}</td></tr>
</table>

<p style="margin-top:20px;">
    Dicetak pada: {{ now()->format('d-m-Y') }}
</p>

</body>
</html>