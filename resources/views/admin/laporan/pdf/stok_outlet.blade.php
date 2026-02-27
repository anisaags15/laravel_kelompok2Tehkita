<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Stok - {{ $outlet->nama_outlet }}</title>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">Laporan Stok Outlet</div>
                    <div style="color: #059669; font-weight: bold; font-size: 12px;">#STK-OUT-{{ str_pad($outlet->id, 5, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td class="company-info">
                    <strong>Dicetak Pada:</strong> {{ now()->translatedFormat('d F Y') }}<br>
                    <strong>Admin:</strong> Sistem Inventaris
                </td>
            </tr>
        </table>
    </div>

    <div class="info-card">
        <table>
            <tr>
                <td class="label">Nama Outlet</td>
                <td>: <strong>{{ $outlet->nama_outlet }}</strong></td>
            </tr>
            <tr>
                <td class="label">Lokasi/Alamat</td>
                <td>: {{ $outlet->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status Data</td>
                <td class="status-verified">: Terverifikasi Sistem</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="40">No</th>
                <th class="text-left">Nama Bahan Baku</th>
                <th width="120">Sisa Stok</th>
                <th width="100">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stok as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-left">
                    <strong>{{ $item->bahan->nama_bahan }}</strong><br>
                    <small style="color: #888;">{{ $item->bahan->kategori->nama_kategori ?? 'Bahan Baku' }}</small>
                </td>
                <td>
                    <span class="stok-value {{ $item->stok <= 5 ? 'stok-kritis' : '' }}">
                        {{ $item->stok }}
                    </span>
                </td>
                <td>{{ $item->bahan->satuan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 40px; color: #999;">
                    Tidak ada data stok yang tersedia saat ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-wrapper">
        <div class="signature-box">
            <p>Manajer Operasional,</p>
            <div class="signature-space"></div>
            <p><strong>( ____________________ )</strong></p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh sistem inventaris. Data stok mencerminkan kondisi gudang outlet saat laporan dicetak.
    </div>

</body>
</html>