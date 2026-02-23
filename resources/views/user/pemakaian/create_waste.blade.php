@extends('layouts.main')

@section('content')
{{-- Tarik paksa ke atas dengan margin-top negatif ekstrem --}}
<div class="content-header p-0" style="margin-top: -25px;"> 
    <div class="container-fluid">
        <div class="row pt-0 pb-3"> 
            <div class="col-sm-12">
                <h1 class="m-0 font-weight-bold text-dark" style="letter-spacing: -1.5px; font-size: 2.2rem; line-height: 1;">
                    <i class="fas fa-recycle text-danger mr-2"></i>Manajemen Waste
                </h1>
                <p class="text-muted small mb-0 mt-1">Lapor bahan baku rusak atau kadaluwarsa secara real-time.</p>
            </div>
        </div>
    </div>
</div>

<section class="content mt-2">
    <div class="container-fluid">
        {{-- Row Statistik Singkat --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="info-box shadow-sm border-0 bg-white" style="min-height: 70px;">
                    <span class="info-box-icon bg-danger-soft" style="background-color: #fff5f5; width: 60px;">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 1.2rem;"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted small">Waste Bulan Ini</span>
                        {{-- REVISI: Angka 12 diganti jadi variabel dinamis --}}
                        <span class="info-box-number text-dark" style="font-size: 1.1rem;">
                            {{ $wasteBulanIni ?? 0 }} <small class="text-secondary font-weight-normal text-xs">Item</small>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Form Input Utama --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom-0 pt-4 pl-4">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-clipboard-check text-secondary mr-2"></i>Form Laporan Kerusakan
                        </h3>
                    </div>
                    
                    {{-- Pastikan route ini sesuai dengan di web.php kamu --}}
                    <form action="{{ route('user.waste.store') }}" method="POST">
                        @csrf
                        <div class="card-body px-4 pt-0">
                            {{-- Notifikasi Sukses --}}
                            @if(session('success'))
                                <div class="alert alert-success border-0 shadow-sm mb-4">
                                    <i class="icon fas fa-check mr-2"></i> {{ session('success') }}
                                </div>
                            @endif

                            {{-- Notifikasi Error --}}
                            @if(session('error'))
                                <div class="alert alert-danger border-0 shadow-sm mb-4">
                                    <i class="icon fas fa-ban mr-2"></i> {{ session('error') }}
                                </div>
                            @endif

                            <div class="form-group mb-4">
                                <label class="text-secondary small font-weight-bold mb-2">PILIH BAHAN BAKU</label>
                                <select name="stok_outlet_id" class="form-control select2 custom-select-lg @error('stok_outlet_id') is-invalid @enderror">
                                    <option value="" selected disabled>Cari bahan di stok...</option>
                                    @foreach($stokOutlets as $stok)
                                        <option value="{{ $stok->id }}">
                                            {{ strtoupper($stok->bahan->nama_bahan) }} — (Sisa: {{ $stok->stok }} {{ $stok->bahan->satuan }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('stok_outlet_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-secondary small font-weight-bold mb-2">JUMLAH KERUSAKAN</label>
                                        <div class="input-group input-group-lg shadow-none">
                                            <input type="number" name="jumlah" class="form-control border-right-0 bg-light @error('jumlah') is-invalid @enderror" placeholder="0" min="1">
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-light text-muted small font-weight-bold">UNIT</span>
                                            </div>
                                        </div>
                                        @error('jumlah') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-secondary small font-weight-bold mb-2">KATEGORI ALASAN</label>
                                        <select name="keterangan" class="form-control form-control-lg custom-select bg-light">
                                            <option value="Basi / Expired">⚠️ Basi / Expired</option>
                                            <option value="Tumpah / Rusak Fisik">🩹 Tumpah / Rusak Fisik</option>
                                            <option value="Salah Produksi">❌ Salah Produksi</option>
                                            <option value="Lainnya">🔍 Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top-0 p-4 text-right">
                            <a href="{{ route('user.dashboard') }}" class="btn btn-link text-secondary mr-3 mt-1">Batal</a>
                            <button type="submit" class="btn btn-danger px-5 py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar Info --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="background: #1a1a1a; border-radius: 12px;">
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-warning mb-3"><i class="fas fa-shield-alt mr-2"></i>Keamanan Data</h6>
                        <p class="small mb-3 text-white-50" style="line-height: 1.6;">Laporan ini bersifat <strong>permanen</strong>. Setiap input akan dicatat ke dalam log audit sistem pusat.</p>
                        <div class="py-2 px-3 bg-dark rounded border border-secondary">
                            <span class="text-white-50 small text-xs">Waktu Sesi:</span><br>
                            <span class="text-white font-weight-bold small"><i class="far fa-clock mr-1 text-warning"></i> {{ date('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-3" style="border-radius: 12px;">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-info-circle mr-2 text-info"></i>Pedoman</h6>
                        <ul class="pl-3 mb-0 small text-muted" style="line-height: 1.8;">
                            <li>Pastikan jumlah tidak melebihi stok tersedia.</li>
                            <li>Pilih kategori "Lainnya" jika kerusakan disebabkan faktor eksternal.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "-- Cari bahan baku --"
        });
    });
</script>
@endpush
@endsection