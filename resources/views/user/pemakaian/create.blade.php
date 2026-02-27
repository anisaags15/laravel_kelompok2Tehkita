@extends('layouts.main')

@section('title', 'Pemakaian Bahan')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="font-weight-bold text-dark mb-1" style="letter-spacing: -1px;">Input Pemakaian</h2>
        <p class="text-muted">Catat penggunaan bahan baku dengan teliti.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-body p-4 p-md-5">
                    
                    @if(session('error'))
                        <div class="alert alert-custom bg-soft-danger text-danger border-0 mb-4" role="alert" style="border-radius: 12px;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle mr-3 fa-lg"></i>
                                <div>
                                    <strong>Gagal!</strong> {{ session('error') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('user.pemakaian.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-4">
                            <label class="form-label-custom">Pilih Bahan Baku</label>
                            <div class="input-group-modern">
                                <div class="input-icon"><i class="fas fa-box"></i></div>
                                <select name="bahan_id" class="form-control select2 custom-select-modern" required>
                                    <option value="">Cari atau pilih bahan...</option>
                                    @forelse($stokOutlets as $stok)
                                        @if($stok->bahan)
                                            <option value="{{ $stok->bahan->id }}">
                                                {{ $stok->bahan->nama_bahan }} — (Tersedia: {{ $stok->stok }} {{ $stok->bahan->satuan }})
                                            </option>
                                        @endif
                                    @empty
                                        <option value="" disabled>Stok kosong, hubungi admin</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label-custom">Jumlah Digunakan</label>
                                    <div class="input-group-modern">
                                        <div class="input-icon"><i class="fas fa-minus-circle text-danger"></i></div>
                                        <input type="number" name="jumlah" class="form-control input-modern" min="1" placeholder="0" required>
                                    </div>
                                    <small class="text-muted mt-2 d-block">*Gunakan angka bulat sesuai satuan.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label-custom">Tanggal Pemakaian</label>
                                    <div class="input-group-modern">
                                        <div class="input-icon"><i class="fas fa-calendar-alt"></i></div>
                                        <input type="date" name="tanggal" class="form-control input-modern" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-between">
                            {{-- PERBAIKAN DI SINI: Nama rute disesuaikan dengan web.php --}}
                            <a href="{{ route('user.riwayat_pemakaian') }}" class="btn btn-link text-muted font-weight-bold p-0">
                                <i class="fas fa-arrow-left mr-1"></i> Batal
                            </a>
                            
                            <button type="submit" class="btn btn-primary-modern shadow-primary px-5 py-3">
                                <i class="fas fa-check-circle mr-2"></i> Simpan Data Pemakaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: #eef2f7;">
                <div class="card-body">
                    <h6 class="font-weight-bold text-dark d-flex align-items-center mb-3">
                        <i class="fas fa-lightbulb text-warning mr-2"></i> Tips Input
                    </h6>
                    <ul class="small text-muted pl-3 mb-0" style="line-height: 1.6;">
                        <li class="mb-2">Pastikan bahan yang dipilih sesuai dengan fisik di outlet.</li>
                        <li class="mb-2">Input dilakukan segera setelah bahan digunakan agar stok tetap <strong>real-time</strong>.</li>
                        <li>Sistem otomatis akan menolak jika jumlah pemakaian melebihi sisa stok.</li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white;">
                <div class="card-body p-4">
                    <i class="fas fa-shield-alt fa-3x mb-3 opacity-20"></i>
                    <h5 class="font-weight-bold">Keamanan Stok</h5>
                    <p class="small mb-0">Semua data pemakaian yang disimpan akan tercatat permanen untuk audit berkala oleh pusat.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection