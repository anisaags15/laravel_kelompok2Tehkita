<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {{-- Memanggil file CSS eksternal menggunakan public_path --}}
    <link rel="stylesheet" href="{{ public_path('templates/dist/css/pdf-laporan-waste.css') }}">
</head>
<body>
    <div class="header">
        @if(isset($logoBase64))
            <img src="{{ $logoBase64 }}" class="logo">
        @endif
        <h2 style="color: #dc3545; margin:0;">LAPORAN KERUSAKAN BAHAN (WASTE)</h2>
        <p style="margin: 5px 0;">
            Outlet: <strong>{{ $outlet->nama_outlet }}</strong> | 
            Periode: <strong>{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</strong>
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th width="20%">Bahan Baku</th>
                <th width="15%">Bukti Foto</th>
                <th width="10%">Jumlah</th>
                <th width="10%">Satuan</th>
                <th width="28%">Alasan / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $totalWaste = 0; @endphp
            @forelse($wasteData as $key => $w)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($w->tanggal)->format('d/m/Y') }}</td>
                <td class="text-left"><strong>{{ $w->stokOutlet->bahan->nama_bahan ?? 'Bahan Terhapus' }}</strong></td>
                <td>
                    @if($w->foto && file_exists(public_path('storage/' . $w->foto)))
                        <img src="{{ public_path('storage/' . $w->foto) }}" class="img-waste">
                    @else
                        <span style="color: #999; font-size: 8px;">No Photo</span>
                    @endif
                </td>
                <td>{{ $w->jumlah }}</td>
                <td>{{ $w->stokOutlet->bahan->satuan ?? '-' }}</td>
                <td class="text-left" style="font-style: italic;">{{ $w->keterangan }}</td>
            </tr>
            @php $totalWaste += $w->jumlah; @endphp
            @empty
            <tr>
                <td colspan="7" style="padding: 30px; color: #999;">Tidak ada data laporan waste pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if($wasteData->count() > 0)
        <tfoot>
            <tr>
                <td colspan="4" class="text-left">TOTAL ITEM WASTE</td>
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