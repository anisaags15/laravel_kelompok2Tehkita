<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DISTRIBUSI BULANAN</h2>
        <p>Outlet: {{ $outlet->nama_outlet }} | Periode: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Kirim</th>
                <th>Nama Bahan Baku</th>
                <th>Kuantitas</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                <td>{{ $item->bahan->nama_bahan }}</td>
                <td><strong>{{ $item->jumlah }}</strong></td>
                <td>{{ $item->bahan->satuan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        Total Item Terdistribusi: {{ number_format($items->sum('jumlah')) }} unit
    </div>
</body>
</html>