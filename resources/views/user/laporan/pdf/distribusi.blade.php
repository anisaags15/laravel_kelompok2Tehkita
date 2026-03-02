<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* Setup Dasar */
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        @page { margin: 1cm; }

        /* Watermark Halus */
        .watermark {
            position: fixed;
            top: 25%; left: 10%; transform: rotate(-45deg);
            font-size: 70px; color: rgba(34, 197, 94, 0.05);
            z-index: -1000; font-weight: bold; text-transform: uppercase;
        }
        
        /* Header & Garis Pembatas */
        .header { border-bottom: 3px double #16a34a; padding-bottom: 10px; margin-bottom: 20px; text-align: center; position: relative; }
        .brand { font-size: 26px; font-weight: bold; color: #16a34a; margin: 0; letter-spacing: 2px; }
        .address { font-size: 9px; color: #666; margin: 0; font-style: italic; }
        
        /* Logo Box (Pojok Kanan Atas) */
        .logo-box { 
            position: absolute; 
            top: -10px; /* Sedikit naik agar sejajar brand */
            right: 0; 
            text-align: center; 
            width: 80px; 
        }
        .logo-box img {
            display: block;
            margin: 0 auto;
        }
        .validation-label {
            font-size: 7px; 
            color: #16a34a; 
            margin-top: 2px; 
            text-transform: uppercase; 
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* Judul Laporan */
        .report-title { 
            font-size: 14px; 
            font-weight: bold; 
            background: #f0fdf4; 
            color: #16a34a; 
            padding: 10px; 
            margin-bottom: 15px; 
            text-align: center; 
            border-radius: 5px; 
            border: 1px solid #dcfce7; 
        }

        /* Info Tabel Atas */
        .report-info { margin-bottom: 15px; width: 100%; border: none; }
        
        /* Styling Tabel Data */
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th { background-color: #16a34a; color: white; padding: 10px; text-transform: uppercase; font-size: 10px; border: 1px solid #15803d; }
        td { padding: 8px; border: 1px solid #e5e7eb; text-align: center; }
        tr:nth-child(even) { background-color: #f9fafb; }
        
        /* Footer & Tanda Tangan */
        .footer-section { margin-top: 30px; }
        .summary-card { float: left; width: 35%; border-left: 4px solid #16a34a; padding: 10px; background: #f9fafb; border: 1px solid #eee; }
        .signature-box { float: right; width: 25%; text-align: center; }
        
        /* Catatan Kaki */
        .notes { clear: both; margin-top: 40px; font-size: 8px; color: #9ca3af; border-top: 1px dashed #e5e7eb; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="watermark">DOKUMEN ASLI</div>

    <div class="header">
        <div class="logo-box">
            @if(isset($logoBase64) && $logoBase64 != null)
                {{-- Logo Distribusi Kamu --}}
                <img src="{{ $logoBase64 }}" width="65">
            @else
                {{-- Cadangan jika file logo tidak terbaca --}}
                <div style="border: 1px solid #16a34a; padding: 5px; font-size: 8px; color: #16a34a; font-weight: bold;">
                    TEH KITA
                </div>
            @endif
            <div class="validation-label">Sistem Valid</div>
        </div>

        <h1 class="brand">TEH KITA</h1>
        <p class="address">Sistem Manajemen Stok & Distribusi Outlet Terpadu<br>Cirebon, Jawa Barat - Indonesia | www.tehkita.id</p>
    </div>

    <div class="report-title">LAPORAN DISTRIBUSI BAHAN BAKU</div>

    <table class="report-info">
        <tr>
            <td style="border:none; text-align:left; width: 12%;"><strong>Outlet</strong></td>
            <td style="border:none; text-align:left;">: {{ $outlet->nama_outlet ?? 'Semua Outlet' }}</td>
            <td style="border:none; text-align:right; color: #666;">ID Laporan: #{{ strtoupper(substr(md5(now()), 0, 8)) }}</td>
        </tr>
        <tr>
            <td style="border:none; text-align:left;"><strong>Periode</strong></td>
            <td style="border:none; text-align:left;">: {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }} s/d {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Sekarang' }}</td>
            <td style="border:none; text-align:right; color: #666;">Dicetak: {{ now()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Waktu Terima</th>
                <th style="text-align:left;">Detail Bahan Baku</th>
                <th width="15%">Kuantitas</th>
                <th width="15%">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distribusi as $key => $item)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                <td style="text-align:left; font-weight: bold;">{{ $item->bahan->nama_bahan }}</td>
                <td style="font-weight:bold; color: #16a34a;">+{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ $item->bahan->satuan ?? 'Unit' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">Tidak ada data distribusi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-section">
        <div class="summary-card">
            <strong style="color: #16a34a;">Ringkasan Data:</strong><br>
            Total Item Masuk: <strong>{{ number_format($distribusi->sum('jumlah'), 0, ',', '.') }} unit</strong><br>
            Frekuensi: <strong>{{ $distribusi->count() }} Transaksi</strong>
        </div>
        
        <div class="signature-box">
            <p style="margin-bottom: 50px;">Admin Outlet,</p>
            <strong style="text-decoration: underline;">( {{ auth()->user()->nama }} )</strong>
            <p style="font-size: 8px; color: #999; margin-top: 5px;">NIP: {{ auth()->user()->id }}{{ now()->format('dmY') }}</p>
        </div>
    </div>

    <div class="notes">
        * Dokumen ini sah dan dihasilkan secara otomatis oleh Sistem Manajemen Teh Kita pada {{ now()->timezone('Asia/Jakarta')->translatedFormat('d/m/Y H:i') }} WIB.<br>
        * Segala bentuk perbedaan data fisik wajib dilaporkan maksimal 1x24 jam setelah barang diterima.
    </div>

</body>
</html>