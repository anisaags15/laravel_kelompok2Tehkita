<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris - {{ $outlet->nama_outlet }}</title>
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

        /* Branding Colors */
        .text-green { color: #1a7a4a; }
        .text-danger { color: #d63031; } /* Warna merah untuk stok kritis */
        .bg-green { background-color: #1a7a4a; color: white; }
        
        /* Layout Helpers */
        .w-100 { width: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        /* Header Section */
        .header-wrapper {
            border-bottom: 2px solid #1a7a4a;
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

        /* Info Outlet Section */
        .info-table {
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .info-box {
            padding: 12px;
            background-color: #f9fbf9; /* Hijau sangat muda agar elegan */
            border: 1px solid #e1e8e1;
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
            table-layout: fixed; /* Menjaga lebar kolom tetap konsisten */
        }
        .stok-table th {
            background-color: #1a7a4a;
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
            background: #e6f6ec;
            color: #1a7a4a;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            border: 1px solid #c2e6d1;
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
                    <h1 class="title text-green uppercase">Laporan Stok Material</h1>
                    <p class="subtitle">Sistem Manajemen Inventaris Digital — Teh Kita</p>
                </td>
                <td class="text-right" style="vertical-align: top;">
                    <div style="font-size: 11pt; font-weight: bold; color: #b2bec3; margin-top: 5px;">
                        #AUDIT-{{ date('YmdHi') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table w-100">
        <tr>
            <td width="65%" style="padding-right: 15px;">
                <div class="info-box">
                    <div class="label">Nama Titik Distribusi (Outlet)</div>
                    <div class="value">{{ $outlet->nama_outlet }}</div>
                    <div style="height: 10px;"></div>
                    <div class="label">Lokasi Alamat</div>
                    <div class="value" style="font-weight: normal; font-size: 9pt; line-height: 1.2;">
                        {{ $outlet->lokasi_outlet ?? 'Alamat pusat belum dikonfigurasi.' }}
                    </div>
                </div>
            </td>
            <td width="35%">
                <div class="info-box">
                    <div class="label">Tanggal Laporan</div>
                    <div class="value">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                    <div style="height: 10px;"></div>
                    <div class="label">Status Verifikasi</div>
                    <div class="value"><span class="badge-status">TERVERIFIKASI PUSAT</span></div>
                </div>
            </td>
        </tr>
    </table>

    <table class="stok-table">
        <thead>
            <tr>
                <th width="8%" class="text-center">No</th>
                <th width="42%">Deskripsi Bahan Baku</th>
                <th width="25%" class="text-center">Log Terakhir</th>
                <th width="25%" class="text-center">Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stok as $item)
            <tr>
                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                <td>
                    <div class="font-bold" style="font-size: 10pt;">{{ $item->bahan->nama_bahan }}</div>
                    <div class="text-muted uppercase" style="font-size: 7pt; letter-spacing: 0.3px;">
                        {{ $item->bahan->kategori->nama_kategori ?? 'Varian Stok' }}
                    </div>
                </td>
                <td class="text-center">
                    @if($item->tanggal_terakhir_diterima)
                        <div class="font-bold" style="font-size: 8.5pt;">
                            {{ \Carbon\Carbon::parse($item->tanggal_terakhir_diterima)->translatedFormat('d M Y') }}
                        </div>
                        <div class="text-muted" style="font-size: 7pt;">Pukul {{ \Carbon\Carbon::parse($item->tanggal_terakhir_diterima)->format('H:i') }} WIB</div>
                    @else
                        <span class="text-muted" style="font-style: italic;">No Activity</span>
                    @endif
                </td>
                <td class="text-center">
                    {{-- Logic Stok 0 berwarna merah --}}
                    <div class="font-bold {{ $item->stok <= 0 ? 'text-danger' : '' }}" style="font-size: 11pt;">
                        {{ $item->stok }} 
                        <span style="font-size: 8pt; font-weight: normal; color: #636e72;">{{ $item->bahan->satuan }}</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 50px;">
                    <span class="text-muted">Tidak ditemukan data persediaan bahan baku.</span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-wrapper">
        <div class="signature-box">
            <p style="margin: 0; font-size: 9pt;">Dicetak dan Disetujui Oleh,</p>
            <p class="text-muted" style="margin: 2px 0;">Sistem Inventaris Teh Kita Pusat</p>
            <div class="signature-line"></div>
            <p class="font-bold uppercase" style="margin: 0; font-size: 9pt;">Management Warehouse</p>
            <p class="text-muted" style="margin: 0; font-size: 7pt;">ID: {{ auth()->user()->id ?? 'SYS-ADM' }} / {{ date('Y') }}</p>
        </div>
    </div>

    <div class="footer-note">
        <strong>PENTING:</strong> Dokumen ini sah dan dihasilkan secara otomatis oleh sistem manajemen inventaris Teh Kita. 
        Data stok diambil berdasarkan sinkronisasi terakhir pada {{ date('d/m/Y H:i:s') }} WIB. 
        Segala bentuk manipulasi data dalam dokumen ini akan ditindaklanjuti sesuai prosedur operasional perusahaan.
    </div>

</body>
</html>