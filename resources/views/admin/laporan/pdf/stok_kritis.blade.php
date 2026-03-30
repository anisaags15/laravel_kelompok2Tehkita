<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Kritis - Teh Kita</title>
    <style>
        /* Pengaturan Dasar & Reset */
        @page { 
            margin: 1.5cm; 
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #2d3436;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        /* Branding Colors (TEMA MERAH) */
        .text-red { color: #d63031; }
        .text-danger { color: #d63031; } 
        .bg-red { background-color: #d63031; color: white; }
        
        /* Layout Helpers */
        .w-100 { width: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        /* Header Section */
        .header-wrapper {
            border-bottom: 2px solid #d63031;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .subtitle {
            font-size: 9pt;
            color: #636e72;
            margin: 2px 0 0 0;
        }

        /* Info Section */
        .info-table {
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .info-box {
            padding: 12px;
            background-color: #fff5f5; /* Merah sangat muda */
            border: 1px solid #feb2b2;
            border-radius: 6px;
        }
        .label { 
            font-size: 7pt; 
            color: #95a5a6; 
            text-transform: uppercase; 
            font-weight: bold;
            margin-bottom: 2px;
        }
        .value { 
            font-size: 10pt; 
            font-weight: bold; 
            color: #2d3436; 
        }

        /* Table Styling */
        .stok-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .stok-table th {
            background-color: #d63031;
            color: #ffffff;
            padding: 10px;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stok-table td {
            padding: 10px;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .stok-table tr:nth-child(even) { background-color: #fdfdfd; }

        /* Status & Badge */
        .badge-status {
            display: inline-block;
            padding: 3px 8px;
            background: #fff5f5;
            color: #d63031;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            border: 1px solid #feb2b2;
        }
        .text-muted { color: #b2bec3; font-size: 8pt; }

        /* Signature Section */
        .signature-wrapper {
            margin-top: 50px;
            width: 100%;
        }
        .signature-box {
            float: right;
            width: 220px;
            text-align: center;
        }
        .signature-line {
            margin-top: 50px;
            border-bottom: 1px solid #2d3436;
            margin-bottom: 5px;
        }

        /* Footer Note */
        .footer-note {
            clear: both;
            margin-top: 60px;
            font-size: 7.5pt;
            color: #95a5a6;
            border-top: 1px solid #eee;
            padding-top: 10px;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <table class="w-100">
            <tr>
                <td>
                    <h1 class="title text-red uppercase">Laporan Stok Kritis</h1>
                    <p class="subtitle">Sistem Manajemen Inventaris Digital — Teh Kita</p>
                </td>
                <td class="text-right" style="vertical-align: top;">
                    <div style="font-size: 11pt; font-weight: bold; color: #b2bec3; margin-top: 5px;">
                        #CRITICAL-{{ date('YmdHi') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table w-100">
        <tr>
            <td width="65%" style="padding-right: 15px;">
                <div class="info-box">
                    <div class="label">Cakupan Wilayah / Outlet</div>
                    <div class="value">
                        {{ request('outlet_id') ? ($stokKritis->first()->outlet->nama_outlet ?? 'N/A') : 'Seluruh Cabang Outlet' }}
                    </div>
                    <div style="height: 10px;"></div>
                    <div class="label">Klasifikasi Urgensi</div>
                    <div class="value text-red" style="font-size: 9pt;">
                        ⚠️ Stok Sangat Rendah (Ambang Batas ≤ 5 Unit)
                    </div>
                </div>
            </td>
            <td width="35%">
                <div class="info-box">
                    <div class="label">Tanggal Laporan</div>
                    <div class="value">{{ date('d F Y') }}</div>
                    <div style="height: 10px;"></div>
                    <div class="label">Status Verifikasi</div>
                    <div class="value"><span class="badge-status">PRIORITAS TINGGI</span></div>
                </div>
            </td>
        </tr>
    </table>

    <table class="stok-table">
        <thead>
            <tr>
                <th width="8%" class="text-center">No</th>
                <th width="30%">Nama Outlet</th>
                <th width="37%">Deskripsi Bahan Baku</th>
                <th width="25%" class="text-center">Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stokKritis as $index => $s)
            <tr>
                <td class="text-center text-muted">{{ $index + 1 }}</td>
                <td>
                    <div class="font-bold">{{ $s->outlet->nama_outlet }}</div>
                    <div class="text-muted" style="font-size: 7pt;">ID: {{ $s->outlet_id }}</div>
                </td>
                <td>
                    <div class="font-bold" style="font-size: 10pt;">{{ $s->bahan->nama_bahan }}</div>
                    <div class="text-muted uppercase" style="font-size: 7pt;">
                        {{ $s->bahan->kategori->nama_kategori ?? 'Varian Stok' }}
                    </div>
                </td>
                <td class="text-center">
                    <div class="font-bold text-danger" style="font-size: 12pt;">
                        {{ $s->stok }} 
                        <span style="font-size: 8pt; font-weight: normal; color: #636e72;">{{ strtoupper($s->bahan->satuan) }}</span>
                    </div>
                    <div class="badge-status" style="margin-top: 5px;">RESTOCK SEGERA</div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 50px;">
                    <span class="text-muted">Luar biasa! Tidak ada stok di bawah ambang batas kritis.</span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-wrapper">
        <div class="signature-box">
            <p style="margin: 0; font-size: 9pt;">Disetujui Oleh,</p>
            <p class="text-muted" style="margin: 2px 0;">Sistem Inventaris Teh Kita Pusat</p>
            <div class="signature-line"></div>
            <p class="font-bold uppercase" style="margin: 0; font-size: 9pt;">Management Warehouse</p>
            <p class="text-muted" style="margin: 0; font-size: 7pt;">Auth ID: SYS-RED-{{ date('His') }}</p>
        </div>
    </div>

    <div class="footer-note">
        <strong>INSTRUKSI LOGISTIK:</strong> Laporan ini bersifat instruksional. Tim operasional diwajibkan melakukan pengadaan ulang atau distribusi stok ke outlet terkait dalam kurun waktu 1x24 jam setelah laporan ini diterbitkan. 
        Sinkronisasi data terakhir: {{ date('d/m/Y H:i:s') }} WIB.
    </div>

</body>
</html>