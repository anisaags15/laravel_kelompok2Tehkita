<!DOCTYPE html>
<html>
<head>
    <title>Rekap Stok Wilayah - Teh Kita</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #444; padding-bottom: 10px; margin-bottom: 20px; }
        .outlet-section { margin-bottom: 30px; page-break-inside: avoid; }
        .outlet-name { background: #f4f4f4; padding: 5px 10px; font-size: 14px; font-weight: bold; border-left: 4px solid #28a745; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #e2ece5ff; color: white; padding: 8px; text-align: left; }
        td { border-bottom: 1px solid #eee; padding: 8px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 9px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">LAPORAN REKAPITULASI STOK WILAYAH</h2>
        <p style="margin:5px 0;">Dicetak Pada: {{ date('d F Y H:i') }} | Admin: Sistem Inventaris</p>
    </div>

    @foreach($outlets as $outlet)
    <div class="outlet-section">
        <div class="outlet-name">{{ strtoupper($outlet->nama_outlet) }}</div>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Bahan Baku</th>
                    <th width="20%">Sisa Stok</th>
                    <th width="15%">Satuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($outlet->stokOutlet as $stok)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $stok->bahan->nama_bahan }}</td>
                    <td>{{ $stok->stok }}</td>
                    <td>{{ $stok->bahan->satuan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Tidak ada data stok</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh sistem inventaris Teh Kita.
    </div>
</body>
</html>