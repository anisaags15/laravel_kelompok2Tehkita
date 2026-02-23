<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengirimanDiterimaNotification extends Notification
{
    use Queueable;

    protected $distribusi;

    public function __construct($distribusi)
    {
        $this->distribusi = $distribusi;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'info', // Kita pakai tipe info (warna biru/hijau)
            'title' => '📦 Pengiriman Telah Diterima',
            'message' => 'Outlet ' . $this->distribusi->outlet->nama_outlet . ' telah mengonfirmasi penerimaan barang untuk distribusi #' . $this->distribusi->id,
            'url' => route('admin.distribusi.index'), // Arahkan ke log distribusi
        ];
    }
}