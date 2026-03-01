<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Distribusi - {{ $outlet->nama_outlet }}</title>
    <style>
        /* Pengaturan Kertas */
        @page { margin: 1cm; }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
        }

        /* Header Section */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            color: #1a1a1a;
        }

        .subtitle {
            font-size: 10pt;
            color: #666;
            margin: 0;
        }

        /* Detail Info Section */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            vertical-align: top;
            padding: 2px 0;
        }

        .label { width: 120px; font-weight: bold; }
        .separator { width: 10px; }

        /* Styling Tabel */
        .stok-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .stok-table th {
            background-color: #f2f2f2;
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }

        .stok-table td {
            border: 1px solid #333;
            padding: 8px;
            vertical-align: middle;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Tanda Tangan */
        .signature-wrapper {
            margin-top: 50px;
            width: 100%;
        }

        .signature-box {
            float: left;
            width: 200px;
            text-align: center;
        }

        .footer-note {
            clear: both;
            margin-top: 50px;
            font-size: 9pt;
            font-style: italic;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .badge-id {
            color: #007bff; /* Biru untuk membedakan dengan laporan stok (hijau) */
            font-family: monospace;
            font-weight: bold;
        }

        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <h1 class="title">Laporan Distribusi Bulanan</h1>
                <p class="subtitle">Sistem Manajemen Logistik Teh Kita</p>
            </td>
            <td style="text-align: right; vertical-align: bottom;">
                <span class="badge-id">#DST-OUT-{{ str_pad($outlet->id, 5, '0', STR_PAD_LEFT) }}</span>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="label">Nama Outlet</td>
            <td class="separator">:</td>
            <td><strong>{{ $outlet->nama_outlet }}</strong></td>
            <td class="label">Periode Laporan</td>
            <td class="separator">:</td>
            <td>{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td class="separator">:</td>
            <td>{{ $outlet->lokasi_outlet ?? 'Alamat belum diatur' }}</td>
            <td class="label">Tgl Cetak</td>
            <td class="separator">:</td>
            <td>{{ date('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Status Data</td>
            <td class="separator">:</td>
            <td colspan="4" style="color: #007bff; font-weight: bold;">Terekapitulasi Otomatis</td>
        </tr>
    </table>

    <table class="stok-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="40%">Nama Bahan Baku</th>
                <th width="20%">Kuantitas</th>
                <th width="20%">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $item->bahan->nama_bahan }}</strong><br>
                    <small style="color: #666;">Distribusi Pusat</small>
                </td>
                <td class="text-center" style="font-weight: bold; font-size: 11pt;">
                    {{ number_format($item->jumlah) }}
                </td>
                <td class="text-center">{{ $item->bahan->satuan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada aktivitas distribusi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL ITEM TERKIRIM :</td>
                <td class="text-center">{{ number_format($items->sum('jumlah')) }}</td>
                <td class="text-center">Unit</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-wrapper">
        <div class="signature-box">
            <p>Admin Gudang Pusat,</p>
            <br><br><br>
            <p><strong>( ____________________ )</strong></p>
        </div>
    </div>

    <div class="footer-note">
        Dokumen ini merupakan laporan resmi distribusi bahan baku dari pusat ke outlet. Dicetak secara otomatis pada {{ date('d/m/Y H:i') }} melalui sistem inventaris.
    </div>

</body>
</html>