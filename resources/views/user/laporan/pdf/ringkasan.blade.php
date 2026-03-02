<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #444; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; color: #d39e00; }
        .header p { margin: 2px 0; font-size: 12px; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        
        .summary-box { background: #f9f9f9; border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; }
        .summary-box table { width: 100%; border: none; }
        .summary-box td { text-align: center; border: none; }
        .value { display: block; font-size: 18px; font-weight: bold; color: #28a745; }
        .label { font-size: 10px; color: #666; text-uppercase: true; }

        table.main-table { width: 100%; border-collapse: collapse; }
        table.main-table th { background-color: #f2f2f2; color: #333; padding: 8px; border: 1px solid #ccc; text-transform: uppercase; font-size: 10px; }
        table.main-table td { padding: 8px; border: 1px solid #ccc; text-align: center; }
        
        .footer { margin-top: 30px; text-align: right; }
        .signature-space { margin-top: 50px; }
        .watermark { position: fixed; bottom: 0; right: 0; font-size: 9px; color: #ccc; }
    </style>
</head>
<body>

<div class="header">
    <h2>Ringkasan Operasional Outlet</h2>
    <p>Sistem Informasi Manajemen - Teh Kita</p>
</div>

<table class="info-table">
    <tr>
        <td width="15%"><strong>Outlet</strong></td>
        <td width="35%">: {{ $outlet->nama_outlet }}</td>
        <td width="15%"><strong>Periode</strong></td>
        <td width="35%">: {{ $bulan }} {{ $tahun }}</td>
    </tr>
    <tr>
        <td><strong>Dicetak Oleh</strong></td>
        <td>: {{ auth()->user()->name }}</td>
        <td><strong>Tanggal Cetak</strong></td>
        <td>: {{ date('d/m/Y H:i') }}</td>
    </tr>
</table>

<div class="summary-box">
    <table>
        <tr>
            <td>
                <span class="label">Total Item Stok</span>
                <span class="value">{{ $totalStok }}</span>
            </td>
            <td style="border-left: 1px solid #ddd;">
                <span class="label">Total Distribusi</span>
                <span class="value" style="color:#007bff;">{{ $totalDistribusi }}</span>
            </td>
            <td style="border-left: 1px solid #ddd;">
                <span class="label">Peringatan Stok</span>
                <span class="value" style="color:#dc3545;">{{ $stokMenipis }}</span>
            </td>
        </tr>
    </table>
</div>

<h4 style="margin-bottom: 10px;">DATA DETAIL DISTRIBUSI</h4>
<table class="main-table">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="25%">Tanggal Pengiriman</th>
            <th width="45%">Nama Bahan Baku</th>
            <th width="25%">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($distribusi as $key => $item)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
            <td style="text-align: left;">{{ $item->bahan->nama_bahan }}</td>
            <td><strong>{{ $item->jumlah }}</strong></td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p>{{ now()->format('d F Y') }}</p>
    <p style="margin-bottom: 60px;">Penanggung Jawab Outlet,</p>
    <p><strong>( {{ auth()->user()->name }} )</strong></p>
</div>

<div class="watermark">Dokumen ini dihasilkan secara otomatis oleh Sistem Teh Kita.</div>

</body>
</html>