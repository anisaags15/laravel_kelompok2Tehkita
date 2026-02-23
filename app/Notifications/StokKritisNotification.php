<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StokKritisNotification extends Notification
{
    use Queueable;
    protected $stokOutlet;

    public function __construct($stokOutlet)
    {
        $this->stokOutlet = $stokOutlet;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'stok_kritis',
            'title' => '⚠️ Stok Kritis!',
            'message' => 'Stok ' . $this->stokOutlet->bahan->nama_bahan . ' di ' . $this->stokOutlet->outlet->nama_outlet . ' tersisa ' . $this->stokOutlet->stok,
            'url' => route('admin.data-bahan-baku.index'), // Sesuaikan route-mu
        ];
    }
}