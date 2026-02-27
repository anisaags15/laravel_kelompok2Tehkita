<script>
$(document).ready(function() {
    /**
     * 1. OTOMATIS: Notifikasi dari Session Laravel
     * Menangkap session('success') atau session('error') dari Controller
     */
    const successMsg = "{{ session('success') }}";
    const errorMsg = "{{ session('error') }}";

    if (successMsg) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMsg,
            timer: 2500,
            showConfirmButton: false,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            }
        });
    }

    if (errorMsg) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: errorMsg,
            confirmButtonColor: '#4e73df',
        });
    }
});

/**
 * 2. GLOBAL: Konfirmasi Terima Barang (Log Distribusi)
 * Digunakan untuk memproses rute terima barang
 */
function confirmTerima(formId) {
    Swal.fire({
        title: 'Konfirmasi Penerimaan',
        text: "Pastikan jumlah barang yang diterima sudah sesuai dengan fisik di outlet!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check-circle mr-1"></i> Ya, Sudah Sesuai',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(formId);
            
            // Proteksi: Hapus hidden _method jika ada (karena biasanya menggunakan POST/PATCH)
            const methodOverride = form.querySelector('input[name="_method"]');
            if (methodOverride && methodOverride.value === 'DELETE') {
                methodOverride.remove();
            }

            // Animasi Loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memperbarui stok outlet Anda',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            form.submit();
        }
    });
}

/**
 * 3. GLOBAL: Konfirmasi Hapus Data (Log Pemakaian/Lainnya)
 * Menampilkan peringatan sebelum data benar-benar dihapus
 */
function confirmDelete(formId) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak bisa dikembalikan dan stok akan dikembalikan ke sistem!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33', // Merah untuk aksi berbahaya
        cancelButtonColor: '#3085d6', // Biru untuk batal
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus Data',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Animasi Loading saat proses penghapusan
            Swal.fire({
                title: 'Menghapus Data...',
                text: 'Sistem sedang menyesuaikan kembali stok Anda',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            document.getElementById(formId).submit();
        }
    });
}
</script>