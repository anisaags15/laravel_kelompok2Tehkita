<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        @page { margin: 1cm; }

        .watermark {
            position: fixed;
            top: 25%; left: 10%; transform: rotate(-45deg);
            font-size: 70px; color: rgba(34, 197, 94, 0.05);
            z-index: -1000; font-weight: bold; text-transform: uppercase;
        }
        
        .header { border-bottom: 3px double #16a34a; padding-bottom: 10px; margin-bottom: 20px; text-align: center; position: relative; }
        .brand { font-size: 26px; font-weight: bold; color: #16a34a; margin: 0; letter-spacing: 2px; }
        .address { font-size: 9px; color: #666; margin: 0; font-style: italic; }
        
        /* Logo Box Pojok Kanan Atas */
        .logo-box { position: absolute; top: -10px; right: 0; text-align: center; width: 80px; }
        .validation-label { font-size: 7px; color: #16a34a; margin-top: 2px; text-transform: uppercase; font-weight: bold; }

        .report-title { 
            font-size: 14px; font-weight: bold; background: #f0fdf4; color: #16a34a; 
            padding: 10px; margin-bottom: 15px; text-align: center; border-radius: 5px; border: 1px solid #dcfce7; 
        }

        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th { background-color: #16a34a; color: white; padding: 10px; text-transform: uppercase; font-size: 10px; border: 1px solid #15803d; }
        td { padding: 8px; border: 1px solid #e5e7eb; text-align: center; }
        tr:nth-child(even) { background-color: #f9fafb; }
        
        .status-danger { color: #dc2626; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        .status-safe { color: #16a34a; font-size: 9px; }

        .footer-section { margin-top: 30px; }
        .summary-card { float: left; width: 40%; border-left: 4px solid #16a34a; padding: 10px; background: #f9fafb; border: 1px solid #eee; }
        .signature-box { float: right; width: 25%; text-align: center; }
        .notes { clear: both; margin-top: 40px; font-size: 8px; color: #9ca3af; border-top: 1px dashed #e5e7eb; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="watermark">STOK REALTIME</div>

    <div class="header">
        <div class="logo-box">
            @if(isset($logoBase64))
                <img src="{{ $logoBase64 }}" width="65">
            @endif
            <div class="validation-label">Sistem Valid</div>
        </div>
        <h1 class="brand">TEH KITA</h1>
        <p class="address">Sistem Manajemen Stok & Distribusi Outlet Terpadu<br>Cirebon, Jawa Barat - Indonesia | www.tehkita.id</p>
    </div>

    <div class="report-title">LAPORAN INVENTARIS & STOK BAHAN BAKU</div>

    <table style="width: 100%; border: none; margin-bottom: 15px;">
        <tr>
            <td style="border:none; text-align:left; width: 12%;"><strong>Outlet</strong></td>
            <td style="border:none; text-align:left;">: {{ $outlet->nama_outlet }}</td>
            <td style="border:none; text-align:right; color: #666;">ID Laporan: #STK{{ strtoupper(substr(md5(now()), 0, 5)) }}</td>
        </tr>
        <tr>
            <td style="border:none; text-align:left;"><strong>Dicetak</strong></td>
            <td style="border:none; text-align:left;">: {{ now()->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</td>
            <td style="border:none; text-align:right; color: #666;">Status Dokumen: Sah / Digital</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th style="text-align:left;">Deskripsi Bahan Baku</th>
                <th width="20%">Status Indikator</th>
                <th width="15%">Jumlah</th>
                <th width="15%">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stok as $key => $item)
            <tr>
                <td>{{ $key+1 }}</td>
                <td style="text-align:left; font-weight: bold;">{{ $item->bahan->nama_bahan }}</td>
                <td>
                    @if($item->stok <= 5)
                        <span class="status-danger">!! Perlu Re-order</span>
                    @else
                        <span class="status-safe">✓ Tersedia</span>
                    @endif
                </td>
                <td style="font-weight:bold; {{ $item->stok <= 5 ? 'color: #dc2626;' : '' }}">
                    {{ number_format($item->stok, 0, ',', '.') }}
                </td>
                <td>{{ $item->bahan->satuan ?? 'Unit' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-section">
        <div class="summary-card">
            <strong style="color: #16a34a;">Ringkasan Inventaris:</strong><br>
            Total Item Terdaftar: <strong>{{ $stok->count() }} Jenis</strong><br>
            Item Stok Menipis: <strong style="color: #dc2626;">{{ $stok->where('stok', '<=', 5)->count() }} Item</strong>
        </div>
        
        <div class="signature-box">
            <p style="margin-bottom: 50px;">Admin Inventaris,</p>
            <strong style="text-decoration: underline;">( {{ auth()->user()->nama }} )</strong>
            <p style="font-size: 8px; color: #999; margin-top: 5px;">Timestamp: {{ now()->format('dmy/His') }}</p>
        </div>
    </div>

    <div class="notes">
        * Laporan ini dihasilkan secara otomatis dan mencerminkan data stok terakhir dalam sistem.<br>
        * Selisih stok antara sistem dan fisik wajib diverifikasi melalui proses stok opname mingguan.
    </div>

</body>
</html>