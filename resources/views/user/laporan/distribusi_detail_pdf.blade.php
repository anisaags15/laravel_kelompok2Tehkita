<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Distribusi {{ $periode }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f0f0f0; }
        .footer { text-align: right; font-size: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Laporan Distribusi Bulan {{ $periode }}</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Outlet</th>
                <th>Bahan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distribusi as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $item->outlet->nama ?? '-' }}</td>
                <td>{{ $item->bahan->nama ?? '-' }}</td>
                <td>{{ $item->jumlah }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">Data tidak tersedia</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak tanggal {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>