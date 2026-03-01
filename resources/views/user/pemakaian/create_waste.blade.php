@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="mb-4 px-2">
        <h2 class="font-weight-bold mb-1" style="letter-spacing: -1.5px; font-size: 2.2rem;">
            <i class="fas fa-recycle text-danger mr-2"></i>Manajemen Waste
        </h2>
        <p class="text-muted small">Lapor bahan baku rusak atau kadaluwarsa secara real-time.</p>
    </div>

    <div class="row">
        {{-- Row Statistik Singkat --}}
        <div class="col-12 mb-4">
            <div class="info-box-custom shadow-sm border-0">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" 
                     style="width: 50px; height: 50px; background: rgba(220, 53, 69, 0.1);">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                </div>
                <div>
                    <span class="d-block text-muted small uppercase font-weight-bold">Waste Bulan Ini</span>
                    <h4 class="mb-0 font-weight-bold text-dark">{{ $wasteBulanIni ?? 0 }} <small class="text-secondary">Item</small></h4>
                </div>
            </div>
        </div>

        {{-- Form Input Utama --}}
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="font-weight-bold mb-0">Form Laporan Kerusakan</h5>
                </div>
                
                <form action="{{ route('user.waste.store') }}" method="POST">
                    @csrf
                    <div class="card-body p-4">
                        
                        @if(session('success'))
                            <div class="alert alert-success border-0 shadow-sm mb-4">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        {{-- Field: Pilih Bahan --}}
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-secondary mb-2">PILIH BAHAN BAKU</label>
                            <div class="input-group-modern">
                                <div class="input-icon-danger"><i class="fas fa-box"></i></div>
                                <select name="stok_outlet_id" class="form-control select2 custom-select-modern" required>
                                    <option value="" selected disabled>Cari bahan di stok...</option>
                                    @foreach($stokOutlets as $stok)
                                        <option value="{{ $stok->id }}">
                                            {{ strtoupper($stok->bahan->nama_bahan) }} — (Sisa: {{ $stok->stok }} {{ $stok->bahan->satuan }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Field: Jumlah --}}
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold text-secondary mb-2">JUMLAH KERUSAKAN</label>
                                    <div class="input-group-modern">
                                        <div class="input-icon-danger"><i class="fas fa-minus-circle"></i></div>
                                        <input type="number" name="jumlah" class="form-control input-modern" placeholder="0" min="1" required>
                                        <div class="d-flex align-items-center px-3 bg-light border-left">
                                            <span class="small font-weight-bold text-muted">UNIT</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Field: Kategori Alasan --}}
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold text-secondary mb-2">KATEGORI ALASAN</label>
                                    <div class="input-group-modern">
                                        <div class="input-icon-danger"><i class="fas fa-tag"></i></div>
                                        <select name="keterangan" class="form-control input-modern" style="border:none;">
                                            <option value="Basi / Expired">⚠️ Basi / Expired</option>
                                            <option value="Tumpah / Rusak Fisik">🩹 Tumpah / Rusak Fisik</option>
                                            <option value="Salah Produksi">❌ Salah Produksi</option>
                                            <option value="Lainnya">🔍 Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent border-0 px-4 pb-4 d-flex align-items-center justify-content-between">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-link text-secondary font-weight-bold">Batal</a>
                        <button type="submit" class="btn btn-danger px-5 py-2 shadow-sm" style="border-radius: 12px; font-weight: 700; height: 50px;">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    {{-- Sidebar Info --}}
<div class="col-lg-4">
    {{-- Hapus style background dan color manualnya, biarkan CSS yang handle --}}
    <div class="card border-0 shadow-sm mb-3 info-box-custom" style="border-radius: 15px;">
        <div class="card-body p-4">
            <h6 class="font-weight-bold text-warning mb-3">
                <i class="fas fa-shield-alt mr-2"></i>Audit Log
            </h6>
            <p class="small text-adaptive" style="line-height: 1.6;">
                Setiap laporan waste akan diverifikasi oleh Admin Pusat. Pastikan data yang dimasukkan akurat.
            </p>
            {{-- Background jam juga kita buat adaptive --}}
            <div class="mt-3 p-2 rounded clock-wrapper">
                <span class="small opacity-text">Waktu Server:</span><br>
                <span class="small font-weight-bold">
                    <i class="far fa-clock mr-1 text-warning"></i> {{ date('d M Y, H:i') }}
                </span>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Init Select2 dengan style Bootstrap 4
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: "-- Cari bahan baku --"
        });
    });
</script>
@endpush