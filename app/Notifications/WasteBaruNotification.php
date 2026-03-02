<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WasteBaruNotification extends Notification
{
    use Queueable;
    protected $waste;

    public function __construct($waste)
    {
        // Kita pastikan data relasi terbawa agar tidak error saat dipanggil di toDatabase
        $this->waste = $waste;
    }

    public function via($notifiable)
    {
        // Menggunakan database agar muncul di tabel notifications (lonceng)
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // Kita ambil nama bahan dari relasi stokOutlet yang sudah kita buat di Model
        $namaBahan = $this->waste->stokOutlet->bahan->nama_bahan ?? 'Bahan Tidak Diketahui';
        $satuan = $this->waste->stokOutlet->bahan->satuan ?? '';
        $namaOutlet = $this->waste->outlet->nama_outlet ?? 'Outlet';

        return [
            'type' => 'waste',
            'title' => '⚠️ Laporan Waste Baru',
            'message' => "{$namaOutlet} melaporkan waste: {$this->waste->jumlah} {$satuan} {$namaBahan}",
            'waste_id' => $this->waste->id, // Penting untuk tracking
            'url' => route('admin.waste.index'), // Link tujuan saat notifikasi diklik
            'icon' => 'fas fa-recycle text-danger', // Icon untuk di UI
            'created_at' => now()->format('H:i'),
        ];
    }

    // Tambahkan toArray juga sebagai cadangan (opsional tapi disarankan)
    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}