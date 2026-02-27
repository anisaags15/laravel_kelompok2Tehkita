<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Eksekutif - {{ $namaBulanAktif }} {{ $tahunAktif }}</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Laporan Eksekutif Bulanan</h2>
            <p>Periode Analisis: <strong>{{ $namaBulanAktif }} {{ $tahunAktif }}</strong> | Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-label">Total Distribusi</div>
                <div class="stat-value">{{ number_format($totalDistribusi) }}</div>
            </div>
            <div class="stat-box success">
                <div class="stat-label">Stok Masuk</div>
                <div class="stat-value">{{ number_format($stokMasuk) }}</div>
            </div>
            <div class="stat-box info">
                <div class="stat-label">Outlet Aktif</div>
                <div class="stat-value">{{ number_format($outletAktif) }}</div>
            </div>
            <div class="stat-box danger">
                <div class="stat-label">Bahan Kritis</div>
                <div class="stat-value">{{ number_format($stokMenipis) }}</div>
            </div>
        </div>

        <div style="width: 100%;">
            <div style="width: 48%; float: left;">
                <div class="section-title">Peringkat Outlet Teraktif</div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Unit Outlet</th>
                            <th class="text-center">Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($outletTeraktif as $item)
                        <tr>
                            <td class="font-bold">{{ $item->outlet->nama_outlet ?? 'N/A' }}</td>
                            <td class="text-center">{{ $item->total }} Transaksi</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="width: 48%; float: right;">
                <div class="section-title">Bahan Baku Terpopuler</div>
                <table>
                    <thead>
                        <tr>
                            <th>Deskripsi Material</th>
                            <th class="text-center">Total Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bahanTerbanyak as $item)
                        <tr>
                            <td class="font-bold">{{ $item->bahan->nama_bahan ?? 'N/A' }}</td>
                            <td class="text-center">{{ number_format($item->total) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer">
            Dokumen ini dihasilkan secara otomatis oleh Sistem Pengelolaan Tehkita &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>