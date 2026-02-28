<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Kritis - {{ date('d/m/Y') }}</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            color: #333;
            line-height: 1.5;
        }
        .header { 
            text-align: center; 
            border-bottom: 2px solid #444; 
            padding-bottom: 10px;
            margin-bottom: 20px; 
        }
        .header h2 { 
            text-transform: uppercase; 
            margin: 0; 
            font-size: 18px;
            color: #000;
        }
        .header p { margin: 5px 0 0; color: #666; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { border: none; padding: 2px 0; }

        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th { 
            background-color: #f2f2f2; 
            color: #333; 
            border: 1px solid #ccc; 
            padding: 10px 8px;
            text-transform: uppercase;
            font-size: 10px;
        }
        td { 
            border: 1px solid #ccc; 
            padding: 8px; 
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-danger { color: #d9534f; font-weight: bold; }
        
        .footer { 
            margin-top: 30px; 
            text-align: right; 
            font-size: 10px;
            font-style: italic;
            color: #777;
        }
        
        /* Zebra Striping */
        tr:nth-child(even) { background-color: #fafafa; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN MONITORING STOK KRITIS</h2>
        <p>Sistem Manajemen Inventaris Pusat</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Tanggal Cetak</td>
            <td width="2%">:</td>
            <td>{{ date('d F Y') }}</td>
            <td width="15%" style="text-align: right;">Waktu</td>
            <td width="2%" style="text-align: right;">:</td>
            <td width="15%" style="text-align: right;">{{ date('H:i') }} WIB</td>
        </tr>
        <tr>
            <td>Filter Outlet</td>
            <td>:</td>
            <td>{{ request('outlet_id') ? $stokKritis->first()->outlet->nama_outlet ?? 'N/A' : 'Semua Outlet' }}</td>
            <td>Status</td>
            <td>:</td>
            <td style="color: red;">URGENT</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="25%">OUTLET</th>
                <th>NAMA BAHAN BAKU</th>
                <th width="15%">SISA STOK</th>
                <th width="15%">SATUAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stokKritis as $index => $s)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $s->outlet->nama_outlet }}</strong></td>
                <td>{{ $s->bahan->nama_bahan }}</td>
                <td class="text-center text-danger">{{ $s->stok }}</td>
                <td class="text-center">{{ strtoupper($s->bahan->satuan) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px;">
                    Tidak ada data stok kritis yang ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh sistem pada {{ date('d/m/Y H:i:s') }}.</p>
    </div>
</body>
</html>