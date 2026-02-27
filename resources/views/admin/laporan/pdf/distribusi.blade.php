<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Distribusi - {{ $distribusi->id }}</title>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">Detail Distribusi Bahan</div>
                    <div style="color: #059669; font-weight: bold; font-size: 12px;">#TRX-DST-{{ str_pad($distribusi->id, 5, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td class="company-info">
                    <strong>Dicetak Pada:</strong> {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>Oleh:</strong> Admin Pusat
                </td>
            </tr>
        </table>
    </div>

    <div class="info-card">
        <table>
            <tr>
                <td class="label">Tanggal Pengiriman</td>
                <td>: {{ \Carbon\Carbon::parse($distribusi->tanggal)->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Outlet Tujuan</td>
                <td>: <strong>{{ $distribusi->outlet->nama_outlet }}</strong></td>
            </tr>
            <tr>
                <td class="label">Alamat Outlet</td>
                <td>: {{ $distribusi->outlet->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status Distribusi</td>
                <td class="status-verified">: {{ $distribusi->status ?? 'Completed' }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="40">No</th>
                <th class="text-left">Nama Bahan Baku</th>
                <th width="150">Jumlah Distribusi</th>
                <th width="100">Satuan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td class="text-left">
                    <strong>{{ $distribusi->bahan->nama_bahan }}</strong><br>
                    <small style="color: #666;">{{ $distribusi->bahan->kategori->nama_kategori ?? 'Bahan Utama' }}</small>
                </td>
                <td><span style="font-size: 14px; font-weight: bold; color: #065f46;">{{ $distribusi->jumlah }}</span></td>
                <td>{{ $distribusi->bahan->satuan }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-wrapper">
        <div class="signature-box">
            <p>Petugas Gudang,</p>
            <div class="signature-space"></div>
            <p><strong>( ____________________ )</strong></p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        Dokumen ini adalah bukti sah distribusi barang dari Gudang Pusat ke Outlet {{ $distribusi->outlet->nama_outlet }}.
    </div>

</body>
</html>