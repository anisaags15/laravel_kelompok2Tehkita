<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11px; line-height: 1.4; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #dc3545; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { max-width: 150px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #dc3545; color: white; padding: 10px 8px; border: 1px solid #ddd; text-transform: uppercase; }
        td { padding: 8px; border: 1px solid #ddd; text-align: center; }
        .text-left { text-align: left; }
        .footer { margin-top: 40px; width: 100%; }
        .signature-box { float: right; width: 200px; text-align: center; }
        .watermark { color: #f8d7da; font-size: 8px; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        @if(isset($logoBase64))
            <img src="{{ $logoBase64 }}" class="logo">
        @endif
        <h2 style="color: #dc3545; margin:0;">LAPORAN KERUSAKAN BAHAN (WASTE)</h2>
        <p style="margin: 5px 0;">
            Outlet: <strong>{{ $outlet->nama_outlet }}</strong> | 
            Periode: <strong>{{ \Carbon\Carbon::createFromDate(request('tahun'), request('bulan'), 1)->translatedFormat('F Y') }}</strong>
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="30%">Bahan Baku</th>
                <th width="10%">Jumlah</th>
                <th width="10%">Satuan</th>
                <th width="30%">Alasan / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $totalWaste = 0; @endphp
            @forelse($wasteData as $key => $w)
            <tr>
                <td>{{ $key + 1 }}</td>
                {{-- REVISI: Pakai kolom tanggal --}}
                <td>{{ \Carbon\Carbon::parse($w->tanggal)->format('d/m/Y') }}</td>
                {{-- REVISI: Langsung ke relasi bahan --}}
                <td class="text-left"><strong>{{ $w->bahan->nama_bahan ?? 'Bahan Terhapus' }}</strong></td>
                <td>{{ $w->jumlah }}</td>
                <td>{{ $w->bahan->satuan ?? '-' }}</td>
                <td class="text-left" style="font-style: italic;">{{ $w->keterangan }}</td>
            </tr>
            @php $totalWaste += $w->jumlah; @endphp
            @empty
            <tr>
                <td colspan="6" style="padding: 30px; color: #999;">Tidak ada data laporan waste pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if($wasteData->count() > 0)
        <tfoot>
            <tr style="background: #f8f9fa; font-weight: bold;">
                <td colspan="3" class="text-left">TOTAL ITEM WASTE</td>
                <td>{{ $totalWaste }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
            <br><br><br><br>
            <p><strong>( {{ auth()->user()->name }} )</strong><br>Penanggung Jawab Outlet</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="watermark">
        Dokumen ini dihasilkan secara otomatis oleh Sistem Manajemen Distribusi.
    </div>
</body>
</html>