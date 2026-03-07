<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Distribusi - {{ $outlet->nama_outlet }}</title>
    <style>
        @page { margin: 1.2cm; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            color: #2d3436;
            line-height: 1.4;
        }

        /* --- HEADER --- */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #1a7a4a;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .logo-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 2px solid #1a7a4a;
            text-align: center;
        }

        .brand-title {
            font-size: 18pt;
            font-weight: bold;
            color: #1a7a4a;
            text-transform: uppercase;
            margin: 0;
        }

        .report-label {
            background-color: #1e293b;
            color: white;
            padding: 5px 15px;
            font-weight: bold;
            border-radius: 3px;
            display: inline-block;
        }

        /* --- INFO DATA --- */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td { padding: 3px 0; }
        .label { color: #636e72; font-weight: bold; width: 120px; text-transform: uppercase; font-size: 8pt; }
        .value { font-weight: bold; color: #000; }

        /* --- TABLE DATA --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #1a7a4a;
            color: white;
            padding: 10px;
            text-align: center;
            border: 1px solid #1a7a4a;
            text-transform: uppercase;
            font-size: 8pt;
        }
        .data-table td {
            padding: 8px;
            border: 1px solid #dfe6e9;
            vertical-align: top;
        }
        .status-box {
            background-color: #f1f5f9;
            padding: 5px;
            border-left: 3px solid #1a7a4a;
            font-size: 8pt;
        }
        .text-success { color: #1a7a4a; font-weight: bold; }

        /* --- SIGNATURE --- */
        .sig-table { width: 100%; margin-top: 40px; }
        .sig-box { text-align: center; width: 40%; }
        .sig-line { border-bottom: 1px solid #000; width: 150px; margin: 60px auto 5px; }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 7pt;
            color: #94a3b8;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="100">
                <div class="logo-circle">
                    <img src="{{ public_path('images/logo teh kita.png') }}" style="width: 75px; margin-top: 7px;">
                </div>
            </td>
            <td style="padding-left: 15px;">
                <h1 class="brand-title">Teh Kita</h1>
                <p style="margin:0; color:#636e72; font-weight:bold; font-size:8pt;">PREMIUM LOGISTICS SYSTEM</p>
            </td>
            <td style="text-align: right; vertical-align: top;">
                <div class="report-label">LAPORAN DISTRIBUSI</div>
                <p style="font-family:monospace; font-size:8pt; margin-top:5px;">ID: DST/{{ $tahun }}{{ $bulan }}/{{ $outlet->id }}</p>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="label">Nama Outlet</td>
            <td width="10">:</td>
            <td class="value">{{ $outlet->nama_outlet }}</td>
            <td class="label">Periode</td>
            <td width="10">:</td>
            <td class="value">{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td width="10">:</td>
            <td class="value">{{ $outlet->alamat ?? 'Cirebon, Jawa Barat' }}</td>
            <td class="label">Tgl Cetak</td>
            <td width="10">:</td>
            <td class="value">{{ date('d/m/Y H:i') }} WIB</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Jadwal Kirim (Pusat)</th>
                <th>Informasi Bahan Baku</th>
                <th width="12%">Jumlah</th>
                <th width="30%">Status Penerimaan (Outlet)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td style="text-align:center;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</strong><br>
                    <small style="color:#636e72;">Pukul {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</small>
                </td>
                <td>
                    <span style="font-weight:bold;">{{ $item->bahan->nama_bahan }}</span><br>
                    <small>Ref: #DIST-{{ $item->id }}</small>
                </td>
                <td style="text-align:center; font-weight:bold; color:#1a7a4a;">
                    {{ number_format($item->jumlah) }} {{ $item->bahan->satuan }}
                </td>
                <td>
                    <div class="status-box">
                        Diterima Pada:<br>
                        <strong>{{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y | H:i') }}</strong><br>
                        <span class="text-success">✔ Barang Sudah Diterima</span>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc; font-weight:bold;">
                <td colspan="3" style="text-align:right; padding:10px;">TOTAL UNIT TERDISTRIBUSI :</td>
                <td style="text-align:center; color:#1a7a4a;">{{ number_format($items->sum('jumlah')) }}</td>
                <td style="text-align:center; background-color: #1e293b; color:white;">{{ $items->count() }} Pengiriman</td>
            </tr>
        </tfoot>
    </table>

    <table class="sig-table">
        <tr>
            <td class="sig-box">
                <p>Diserahkan Oleh,</p>
                <div class="sig-line"></div>
                <strong>Admin Gudang Pusat</strong>
            </td>
            <td></td>
            <td class="sig-box">
                <p>Diterima Oleh,</p>
                <div class="sig-line"></div>
                <strong>Manager / PIC Outlet</strong>
            </td>
        </tr>
    </table>

    <div class="footer">
        Laporan ini dihasilkan otomatis oleh Sistem Logistik Teh Kita &bull; Halaman 1 dari 1
    </div>

</body>
</html>