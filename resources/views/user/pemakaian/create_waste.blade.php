@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="mb-4 px-2">
        <h2 class="font-weight-bold mb-1 text-dark" style="letter-spacing: -1.5px; font-size: 2.2rem;">
            <i class="fas fa-recycle text-danger mr-2"></i>Manajemen Waste
        </h2>
        <p class="text-muted small">Lapor bahan baku rusak atau kadaluwarsa secara real-time.</p>
    </div>

    <div class="row">
        {{-- Row Statistik Singkat --}}
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm transition-all overflow-hidden" style="border-radius: 15px;">
                <div class="card-body p-3 border-left border-danger" style="border-width: 5px !important;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" 
                             style="width: 45px; height: 45px; background: rgba(220, 53, 69, 0.1);">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                        </div>
                        <div>
                            <span class="d-block text-muted small font-weight-bold text-uppercase" style="font-size: 0.65rem;">Waste Bulan Ini</span>
                            <h4 class="mb-0 font-weight-bold text-dark">{{ $wasteBulanIni ?? 0 }} <small class="text-secondary" style="font-size: 0.8rem">Item</small></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12"></div> {{-- Break row --}}

        {{-- Form Input Utama --}}
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 20px;">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="font-weight-bold mb-0 text-dark">Form Laporan Kerusakan</h5>
                </div>
                
                {{-- Tambahkan enctype untuk upload foto --}}
                <form action="{{ route('user.waste.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-4">
                        
                        @if(session('success'))
                            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        {{-- Field: Pilih Bahan --}}
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted mb-2 text-uppercase">Pilih Bahan Baku</label>
                            <div class="input-group border rounded-pill px-3 py-1 bg-light focus-within-danger">
                                <div class="input-group-prepend border-0">
                                    <span class="input-group-text bg-transparent border-0 text-danger"><i class="fas fa-box"></i></span>
                                </div>
                                <select name="stok_outlet_id" class="form-control select2 border-0 bg-transparent" required>
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
                                    <label class="small font-weight-bold text-muted mb-2 text-uppercase">Jumlah Kerusakan</label>
                                    <div class="input-group border rounded-pill overflow-hidden bg-light">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent border-0 text-danger pl-3"><i class="fas fa-minus-circle"></i></span>
                                        </div>
                                        <input type="number" name="jumlah" class="form-control border-0 bg-transparent py-4" placeholder="0" min="1" step="0.01" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Field: Kategori Alasan --}}
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold text-muted mb-2 text-uppercase">Kategori Alasan</label>
                                    <div class="input-group border rounded-pill overflow-hidden bg-light px-2">
                                        <select name="keterangan" class="form-control border-0 bg-transparent py-2" required>
                                            <option value="Basi / Expired">⚠️ Basi / Expired</option>
                                            <option value="Tumpah / Rusak Fisik">🩹 Tumpah / Rusak Fisik</option>
                                            <option value="Salah Produksi">❌ Salah Produksi</option>
                                            <option value="Lainnya">🔍 Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- NEW Field: Upload Foto Bukti --}}
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-muted mb-2 text-uppercase">Foto Bukti (Wajib)</label>
                            <div class="custom-file border-0">
                                <input type="file" name="foto" class="custom-file-input" id="fotoWaste" accept="image/*" required>
                                <label class="custom-file-label border-0 bg-light rounded-pill" for="fotoWaste">Pilih foto kerusakan...</label>
                            </div>
                            <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle mr-1"></i> Format: JPG, PNG. Maks: 2MB.</small>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 px-4 pb-4 d-flex align-items-center justify-content-between">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-link text-secondary font-weight-bold shadow-none">Batal</a>
                        <button type="submit" class="btn btn-danger px-5 py-2 shadow-sm" style="border-radius: 12px; font-weight: 700; height: 50px;">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3 bg-gradient-dark text-white" style="border-radius: 20px; background: #1a1a1a;">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold text-warning mb-3">
                        <i class="fas fa-shield-alt mr-2"></i>Audit Log System
                    </h6>
                    <p class="small opacity-75" style="line-height: 1.6;">
                        Setiap laporan waste akan diverifikasi oleh Admin Pusat sebelum memotong stok secara permanen.
                    </p>
                    <div class="mt-4 p-3 rounded" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <span class="small opacity-50">Waktu Pelaporan (Server):</span><br>
                        <span class="font-weight-bold text-warning">
                            <i class="far fa-clock mr-1"></i> {{ date('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Info Card Tambahan --}}
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-headset fa-2x text-primary mb-3"></i>
                    <h6 class="font-weight-bold text-dark">Butuh Bantuan?</h6>
                    <p class="small text-muted mb-0">Hubungi admin gudang jika bahan baku tidak ditemukan dalam daftar.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Init Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: "-- Cari bahan baku --"
        });

        // Show filename on upload
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush