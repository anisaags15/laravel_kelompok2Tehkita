<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Laporan Bulanan</title>
    <style>
        @page {
            margin: 50px 40px;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            margin: 0;
        }

        header {
            position: fixed;
            top: -40px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
        }

        header img {
            height: 40px;
            vertical-align: middle;
        }

        header h2 {
            display: inline-block;
            margin: 0 0 0 10px;
            font-size: 16px;
            vertical-align: middle;
        }

        footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 30px;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .container {
            margin-top: 60px;
        }

        .card {
            border: 1px solid #999;
            border-radius: 4px;
            padding: 10px 12px;
            margin-bottom: 15px;
            background: #f9f9f9;
        }

        h3.section-title {
            margin-top: 20px;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        th, td {
            border: 1px solid #999;
            padding: 5px 8px;
            text-align: left;
        }

        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

    </style>
</head>
<body>

<header>
    <img src="{{ public_path('images/logo teh kita.png') }}" 
         alt="Logo" 
         style="height:40px; vertical-align: middle;">
    <h2 style="display:inline-block; margin-left:10px;">Ringkasan Laporan Bulanan</h2>
</header>

<footer>
    Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }} | Halaman <span class="page"></span>
</footer>

<div class="container">
    @php
        $bulanInt = (int) $bulan;
        $tanggal = \Carbon\Carbon::createFromDate($tahun, $bulanInt, 1);
    @endphp
    <p>Bulan: <strong>{{ $tanggal->format('F Y') }}</strong></p>

    {{-- Ringkasan --}}
    <div class="card">
        <h4>Total Distribusi Bulan Ini: {{ $totalDistribusi }}</h4>
    </div>
    <div class="card">
        <h4>Stok Masuk Bulan Ini: {{ $stokMasuk }}</h4>
    </div>
    <div class="card">
        <h4>Outlet Aktif: {{ $outletAktif }}</h4>
    </div>
    <div class="card">
        <h4>Bahan Stok Menipis: {{ $stokMenipis }}</h4>
    </div>

    {{-- Outlet Teraktif --}}
    <h3 class="section-title">Outlet Teraktif (TOP 5)</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Outlet</th>
                <th>Total Distribusi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($outletTeraktif as $item)
                <tr>
                    <td>{{ $item->nama_outlet }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align:center;">Data belum tersedia</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Bahan Terbanyak --}}
    <h3 class="section-title">Bahan Paling Sering Dikirim (TOP 5)</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Bahan</th>
                <th>Total Kirim</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanTerbanyak as $item)
                <tr>
                    <td>{{ $item->nama_bahan }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align:center;">Data belum tersedia</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script("
            if (isset(\$pdf)) {
                \$font = \$fontMetrics->get_font('Times-Roman','normal');
                \$pdf->text(520, 820, 'Halaman ' . \$PAGE_NUM . ' dari ' . \$PAGE_COUNT, \$font, 10);
            }
        ");
    }
</script>

</body>
</html>