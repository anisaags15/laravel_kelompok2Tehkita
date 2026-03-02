@extends('layouts.main')

@section('title', 'Pemakaian Bahan')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4 px-2">
        <h2 class="font-weight-bold mb-1" style="letter-spacing: -1px;">Input Pemakaian</h2>
        <p class="opacity-75">Catat penggunaan bahan baku dengan teliti.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px; overflow: hidden;">
                <div class="card-body p-4 p-md-5">
                    
                    @if(session('error'))
                        <div class="alert bg-danger text-white border-0 mb-4" style="border-radius: 12px;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle mr-3"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('user.pemakaian.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-4">
                            <label class="font-weight-600 mb-2 d-block">Pilih Bahan Baku</label>
                            <div class="input-group-modern">
                                <div class="input-group-prepend-custom">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="flex-grow-1 container-select2">
                                    <select name="bahan_id" class="form-control select2 custom-select-modern" required>
                                        <option value="">Cari atau pilih bahan...</option>
                                        @foreach($stokOutlets as $stok)
                                            @if($stok->bahan)
                                                <option value="{{ $stok->bahan->id }}">
                                                    {{ $stok->bahan->nama_bahan }} — (Tersedia: {{ $stok->stok }} {{ $stok->bahan->satuan }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-600 mb-2 d-block">Jumlah Digunakan</label>
                                    <div class="input-group-modern">
                                        <div class="input-group-prepend-custom">
                                            <i class="fas fa-minus-circle text-danger"></i>
                                        </div>
                                        <input type="number" name="jumlah" class="form-control input-custom-modern" min="1" placeholder="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-600 mb-2 d-block">Tanggal Pemakaian</label>
                                    <div class="input-group-modern">
                                        <div class="input-group-prepend-custom">
                                            <i class="fas fa-calendar-alt text-primary"></i>
                                        </div>
                                        <input type="date" name="tanggal" class="form-control input-custom-modern" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-top border-adaptive d-flex align-items-center justify-content-between">
                            <a href="{{ route('user.riwayat_pemakaian') }}" class="text-muted text-decoration-none font-weight-bold">
                                <i class="fas fa-arrow-left mr-2"></i> Batal
                            </a>
                            
                            <button type="submit" class="btn btn-success px-5 shadow-sm btn-simpan-custom">
                                <i class="fas fa-check-circle mr-2"></i> Simpan Data Pemakaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold d-flex align-items-center mb-3">
                        <i class="fas fa-lightbulb text-warning mr-2"></i> Tips Input
                    </h6>
                    <ul class="small pl-3 mb-0" style="line-height: 1.8; opacity: 0.8;">
                        <li class="mb-2">Pastikan bahan yang dipilih sesuai fisik outlet.</li>
                        <li>Sistem otomatis akan menolak jika jumlah melebihi sisa stok.</li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #198754 0%, #115c3a 100%) !important; color: white;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-shield-alt fa-2x mr-3" style="opacity: 0.6;"></i>
                        <h5 class="font-weight-bold mb-0">Keamanan Stok</h5>
                    </div>
                    <p class="small mb-0" style="opacity: 0.9;">Data pemakaian yang disimpan akan tercatat permanen untuk audit berkala oleh pusat.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection