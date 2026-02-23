<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WasteBaruNotification extends Notification
{
    use Queueable;
    protected $waste;

    public function __construct($waste)
    {
        $this->waste = $waste;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'waste',
            'title' => 'Laporan Waste Baru',
            'message' => 'Outlet ' . $this->waste->outlet->nama_outlet . ' melaporkan waste: ' . $this->waste->jumlah . ' ' . $this->waste->bahan->nama_bahan,
            'url' => route('admin.waste.index'),
        ];
    }
}