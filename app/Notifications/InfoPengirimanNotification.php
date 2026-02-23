<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class InfoPengirimanNotification extends Notification
{
    use Queueable;

    protected $distribusi;

    /**
     * Simpan data distribusi ke dalam class
     */
    public function __construct($distribusi)
    {
        $this->distribusi = $distribusi;
    }

    /**
     * Gunakan channel 'database' agar tersimpan di tabel notifications
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Isi data yang akan disimpan ke kolom 'data' di database
     * Data ini harus cocok dengan variabel di view notifikasi kamu
     */
    public function toArray($notifiable)
    {
        return [
            'type'    => 'info', // Akan dibaca oleh match($type) di Blade kamu
            'title'   => 'Pengiriman Barang Baru',
            'message' => 'Pusat telah mengirim ' . $this->distribusi->jumlah . ' ' . $this->distribusi->bahan->nama_bahan . '. Silakan cek dan konfirmasi.',
            'url'     => route('user.distribusi.index'), // Link ke halaman distribusi masuk
        ];
    }
}