<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok - {{ $outlet->nama_outlet }}</title>
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

        /* Styling Tabel Stok */
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
            color: #28a745;
            font-family: monospace;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <h1 class="title">Laporan Stok Outlet</h1>
                <p class="subtitle">Sistem Manajemen Inventaris Teh Kita</p>
            </td>
            <td style="text-align: right; vertical-align: bottom;">
                <span class="badge-id">#STK-OUT-{{ str_pad($outlet->id, 5, '0', STR_PAD_LEFT) }}</span>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="label">Nama Outlet</td>
            <td class="separator">:</td>
            <td><strong>{{ $outlet->nama_outlet }}</strong></td>
            <td class="label">Dicetak Pada</td>
            <td class="separator">:</td>
            <td>{{ date('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td class="separator">:</td>
            <td>{{ $outlet->lokasi_outlet ?? 'Alamat belum diatur' }}</td>
            <td class="label">Admin</td>
            <td class="separator">:</td>
            <td>Sistem Pusat</td>
        </tr>
        <tr>
            <td class="label">Status Data</td>
            <td class="separator">:</td>
            <td colspan="4" style="color: #28a745; font-weight: bold;">Terverifikasi Sistem</td>
        </tr>
    </table>

    <table class="stok-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="55%">Nama Bahan Baku</th>
                <th width="20%">Sisa Stok</th>
                <th width="20%">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stok as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    <strong>{{ $item->bahan->nama_bahan }}</strong><br>
                    <small style="color: #666;">Bahan Baku</small>
                </td>
                <td class="text-center" style="font-weight: bold; font-size: 12pt;">
                    {{ $item->stok }}
                </td>
                <td class="text-center">{{ $item->bahan->satuan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data stok tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-wrapper">
        <div class="signature-box">
            <p>Manajer Operasional,</p>
            <br><br><br>
            <p><strong>( ____________________ )</strong></p>
        </div>
    </div>

    <div class="footer-note">
        Dokumen ini dihasilkan secara otomatis oleh sistem inventaris. Data stok mencerminkan kondisi gudang outlet saat laporan dicetak pada {{ date('d/m/Y H:i') }}.
    </div>

</body>
</html>