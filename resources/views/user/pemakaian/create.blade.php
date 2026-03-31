@extends('layouts.main')

@section('title', 'Input Pemakaian Bahan')

@section('styles')
<link rel="stylesheet" href="{{ asset('templates/dist/css/create-pemakaian.css') }}">
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4 px-2">
        <h2 class="font-weight-bold mb-1" style="letter-spacing: -1px;">Input Pemakaian Bahan</h2>
        <p class="opacity-75">Catat penggunaan bahan baku harian secara massal.</p>
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

                    <form action="{{ route('user.pemakaian.store') }}" method="POST" id="pemakaianForm">
                        @csrf

                        <div class="form-group mb-4">
                            <label class="font-weight-600 mb-2 d-block">Tanggal Pemakaian</label>
                            <div class="input-group-modern" style="max-width: 300px;">
                                <div class="input-group-prepend-custom">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </div>
                                <input type="date" name="tanggal" class="form-control input-custom-modern" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <label class="font-weight-600 mb-2 d-block">Pilih Bahan & Masukkan Jumlah</label>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush table-hover" id="tablePemakaian">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="50">Pilih</th>
                                        <th>Nama Bahan</th>
                                        <th class="text-center">Stok</th>
                                        <th width="200">Jumlah Pakai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stokOutlets as $index => $stok)
                                    <tr class="item-row">
                                        <td>
                                            <div class="custom-control custom-checkbox ml-2">
                                                <input type="checkbox" name="items[{{ $index }}][checked]" value="1" class="custom-control-input item-checkbox" id="item-{{ $stok->bahan->id }}">
                                                <label class="custom-control-label" for="item-{{ $stok->bahan->id }}"></label>
                                            </div>
                                            <input type="hidden" name="items[{{ $index }}][bahan_id]" value="{{ $stok->bahan->id }}">
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-dark">{{ $stok->bahan->nama_bahan }}</span>
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted d-block">Tersedia:</small>
                                            <span class="badge badge-pill badge-secondary">{{ $stok->stok }} {{ $stok->bahan->satuan }}</span>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="number" step="0.01" name="items[{{ $index }}][jumlah]" class="form-control item-jumlah" placeholder="0" min="0.01" max="{{ $stok->stok }}" disabled required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text bg-transparent border-left-0 small">{{ $stok->bahan->satuan }}</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 pt-4 border-top border-adaptive d-flex align-items-center justify-content-between">
                            <a href="{{ route('user.riwayat_pemakaian') }}" class="text-muted text-decoration-none font-weight-bold">
                                <i class="fas fa-arrow-left mr-2"></i> Batal
                            </a>
                            
                            <button type="submit" class="btn btn-success px-5 shadow-sm btn-simpan-custom" id="btnSimpan" disabled>
                                <i class="fas fa-check-circle mr-2"></i> Simpan Semua Pemakaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4 card-tips">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold d-flex align-items-center mb-3">
                        <i class="fas fa-lightbulb text-warning mr-2"></i> Tips Input
                    </h6>
                    <ul class="small pl-3 mb-0" style="line-height: 1.8; opacity: 0.8;">
                        <li class="mb-2">Centang kotak di sebelah kiri untuk memilih bahan.</li>
                        <li class="mb-2">Input jumlah hanya bisa diisi jika bahan sudah dicentang.</li>
                        <li class="mb-2">Pastikan bahan yang dipilih sesuai fisik outlet.</li>
                        <li>Sistem otomatis akan menolak jika jumlah melebihi sisa stok.</li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm card-security">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const btnSimpan = document.getElementById('btnSimpan');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const row = this.closest('.item-row');
                const inputJumlah = row.querySelector('.item-jumlah');
                
                if (this.checked) {
                    inputJumlah.disabled = false;
                    inputJumlah.focus();
                    row.classList.add('selected-bg');
                } else {
                    inputJumlah.disabled = true;
                    inputJumlah.value = '';
                    row.classList.remove('selected-bg');
                }

                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                btnSimpan.disabled = !anyChecked;
            });
        });

        document.getElementById('pemakaianForm').onsubmit = function() {
            btnSimpan.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
            btnSimpan.classList.add('disabled');
        };
    });
</script>
@endsection