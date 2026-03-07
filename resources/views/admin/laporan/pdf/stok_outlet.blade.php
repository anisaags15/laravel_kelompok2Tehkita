<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris - {{ $outlet->nama_outlet }}</title>
    <style>
        /* Pengaturan Dasar */
        @page { margin: 1.2cm; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #2d3436;
            background-color: #fff;
        }

        /* Branding Colors */
        .text-green { color: #1a7a4a; }
        .bg-green { background-color: #1a7a4a; color: white; }
        .bg-light { background-color: #f8f9fa; }

        /* Header Section */
        .header-wrapper {
            border-bottom: 3px solid #1a7a4a;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .title {
            font-size: 20pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }
        .subtitle {
            font-size: 10pt;
            color: #636e72;
            margin-top: 5px;
            font-weight: normal;
        }

        /* Info Outlet Section (Grid Style) */
        .info-container {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-box {
            padding: 15px;
            background-color: #fcfcfc;
            border: 1px solid #edf2f7;
            border-radius: 8px;
        }
        .label { 
            font-size: 8pt; 
            color: #b2bec3; 
            text-transform: uppercase; 
            font-weight: bold;
            margin-bottom: 3px;
        }
        .value { 
            font-size: 11pt; 
            font-weight: bold; 
            color: #2d3436; 
        }

        /* Table Styling */
        .stok-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .stok-table th {
            background-color: #1a7a4a;
            color: #ffffff;
            padding: 12px 10px;
            text-align: left;
            font-size: 9pt;
            text-transform: uppercase;
            border: none;
        }
        .stok-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
        }
        .stok-table tr:nth-child(even) { background-color: #fafafa; }

        /* Status & Badge */
        .badge-status {
            padding: 4px 8px;
            background: #e6f6ec;
            color: #1a7a4a;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }
        .text-muted { color: #b2bec3; font-size: 8pt; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }

        /* Signature Section */
        .signature-section {
            margin-top: 60px;
            width: 100%;
        }
        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-space {
            margin: 40px 0;
            border-bottom: 1px solid #2d3436;
            width: 200px;
            display: inline-block;
        }

        /* Footer Note */
        .footer-note {
            clear: both;
            margin-top: 80px;
            font-size: 8pt;
            color: #b2bec3;
            border-top: 1px solid #eee;
            padding-top: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <table width="100%">
            <tr>
                <td>
                    <h1 class="title text-green">Laporan Stok Material</h1>
                    <p class="subtitle">Sistem Manajemen Inventaris Digital — Teh Kita</p>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <div style="font-size: 14pt; font-weight: bold; color: #b2bec3;">#AUDIT-{{ date('YmdHi') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-container" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td width="65%" style="padding-right: 20px;">
                <div class="info-box">
                    <div class="label">Nama Titik Distribusi (Outlet)</div>
                    <div class="value">{{ $outlet->nama_outlet }}</div>
                    <div style="margin-top: 10px;"></div>
                    <div class="label">Lokasi Alamat</div>
                    <div class="value" style="font-weight: normal; font-size: 10pt;">
                        {{ $outlet->lokasi_outlet ?? 'Alamat pusat belum dikonfigurasi.' }}
                    </div>
                </div>
            </td>
            <td width="35%">
                <div class="info-box">
                    <div class="label">Tanggal Laporan</div>
                    <div class="value">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                    <div style="margin-top: 10px;"></div>
                    <div class="label">Status Verifikasi</div>
                    <div class="value"><span class="badge-status">TERVERIFIKASI PUSAT</span></div>
                </div>
            </td>
        </tr>
    </table>

    <table class="stok-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="40%">Deskripsi Bahan Baku</th>
                <th width="30%" class="text-center">Log Terakhir Diterima</th>
                <th width="25%" class="text-center">Sisa Stok Fisik</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stok as $item)
            <tr>
                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                <td>
                    <div class="font-bold">{{ $item->bahan->nama_bahan }}</div>
                    <div class="text-muted">{{ strtoupper($item->bahan->kategori->nama_kategori ?? 'Varian Stok') }}</div>
                </td>
                <td class="text-center">
                    @if($item->tanggal_terakhir_diterima)
                        <div class="font-bold" style="font-size: 9pt;">
                            {{ \Carbon\Carbon::parse($item->tanggal_terakhir_diterima)->translatedFormat('d M Y') }}
                        </div>
                        <div class="text-muted">Pukul {{ \Carbon\Carbon::parse($item->tanggal_terakhir_diterima)->format('H:i') }} WIB</div>
                    @else
                        <span class="text-muted">No Activity Log</span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="font-bold" style="font-size: 12pt;">
                        {{ $item->stok }} <span style="font-size: 8pt; font-weight: normal;">{{ $item->bahan->satuan }}</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 40px;">
                    <span class="text-muted">Tidak ditemukan data persediaan bahan baku.</span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p style="margin-bottom: 0;">Dicetak dan Disetujui Oleh,</p>
            <p style="font-size: 8pt; margin-top: 2px;" class="text-muted">Sistem Inventaris Teh Kita Pusat</p>
            <div class="signature-space"></div>
            <p class="font-bold" style="margin-top: 0;">Management Warehouse</p>
        </div>
    </div>

    <div class="footer-note">
        <strong>PENTING:</strong> Dokumen ini merupakan laporan resmi yang dihasilkan secara otomatis oleh sistem Teh Kita. Data stok di atas diambil berdasarkan sinkronisasi terakhir pada {{ date('d/m/Y H:i:s') }} WIB. Seluruh perbedaan data wajib dilaporkan kepada Admin Pusat.
    </div>

</body>
</html>