@extends('layouts.main')

@section('title', 'Kirim Barang')
@section('page', 'Pengiriman Barang ke Outlet')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.distribusi.index') }}" class="btn btn-white btn-sm rounded-circle shadow-sm mr-3" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border: 1px solid #e3e6f0;">
                    <i class="fas fa-chevron-left text-success"></i>
                </a>
                <h3 class="font-weight-bold text-dark mb-0">Form Pengiriman Massal</h3>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-xl" style="border-radius: 24px;">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border-radius: 22px;">
                            <i class="fas fa-truck-loading text-white fa-2x"></i>
                        </div>
                        <h4 class="font-weight-bold">Distribusi Multi-Bahan</h4>
                        <p class="text-muted small">Pilih bahan yang ingin dikirim dan tentukan jumlahnya sekaligus.</p>
                    </div>

                    <form action="{{ route('admin.distribusi.store') }}" method="POST" id="sendForm">
                        @csrf
                        <div class="row">
                            {{-- Outlet --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 d-block">Outlet Tujuan</label>
                                <select name="outlet_id" class="form-control @error('outlet_id') is-invalid @enderror" style="border-radius: 12px; height: 50px; border: 2px solid #e1f5ea;" required>
                                    <option value="" selected disabled>-- Pilih Outlet Tujuan --</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}" {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>{{ $outlet->nama_outlet }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tanggal --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase text-muted mb-2 d-block">Tanggal Pengiriman</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" style="border-radius: 12px; height: 50px; border: 2px solid #e1f5ea;" required>
                            </div>

                            {{-- Checklist Bahan --}}
                            <div class="col-12">
                                <label class="text-xs font-weight-bold text-uppercase text-muted mb-3 d-block">Daftar Bahan Baku</label>
                                <div class="table-responsive">
                                    <table class="table align-items-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="50">Pilih</th>
                                                <th>Nama Bahan</th>
                                                <th>Stok Gudang</th>
                                                <th width="200">Jumlah Kirim</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bahans as $index => $bahan)
                                            <tr class="item-row">
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="items[{{ $index }}][checked]" value="1" class="custom-control-input item-checkbox" id="item-{{ $bahan->id }}">
                                                        <label class="custom-control-label" for="item-{{ $bahan->id }}"></label>
                                                    </div>
                                                    <input type="hidden" name="items[{{ $index }}][bahan_id]" value="{{ $bahan->id }}">
                                                </td>
                                                <td class="font-weight-bold">{{ $bahan->nama_bahan }}</td>
                                                <td><span class="badge badge-pill badge-light border text-dark">{{ $bahan->stok_awal }} {{ $bahan->satuan }}</span></td>
                                                <td>
                                                    <input type="number" name="items[{{ $index }}][jumlah]" class="form-control item-jumlah" placeholder="0" min="1" max="{{ $bahan->stok_awal }}" disabled style="border-radius: 8px;">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg w-100 py-3 shadow-lg" id="btnKirim" style="border-radius: 15px; font-weight: 700; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border: none;" disabled>
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Barang Terpilih
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const row = this.closest('.item-row');
            const inputJumlah = row.querySelector('.item-jumlah');
            
            if (this.checked) {
                inputJumlah.disabled = false;
                inputJumlah.focus();
                row.style.backgroundColor = '#f0fff4';
            } else {
                inputJumlah.disabled = true;
                inputJumlah.value = '';
                row.style.backgroundColor = 'transparent';
            }

            // Aktifkan tombol kirim jika ada yang dicentang
            const anyChecked = Array.from(document.querySelectorAll('.item-checkbox')).some(c => c.checked);
            document.getElementById('btnKirim').disabled = !anyChecked;
        });
    });

    document.getElementById('sendForm').onsubmit = function() {
        let btn = document.getElementById('btnKirim');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sedang Memproses...';
        btn.classList.add('disabled');
    };
</script>
@endsection