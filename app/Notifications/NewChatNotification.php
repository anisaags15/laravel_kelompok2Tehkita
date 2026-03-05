<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewChatNotification extends Notification
{
    use Queueable;

    protected $message;

    /**
     * Terima data pesan dari Controller.
     * Pastikan $message sudah di-load beserta relasi sender-nya:
     * Contoh pemanggilan di Controller:
     *   $message = Message::with('sender')->find($id);
     *   $receiver->notify(new NewChatNotification($message));
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Gunakan channel 'database' agar tersimpan di tabel notifications.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Simpan data ke tabel notifications.
     */
    public function toDatabase(object $notifiable): array
    {
        // Aman dari error kalau relasi sender belum di-load
        $senderName = $this->message->sender->name ?? 'Seseorang';

        return [
            'type'      => 'chat',
            'title'     => 'Pesan Baru dari ' . $senderName,
            'message'   => Str::limit($this->message->message, 50),
            'url'       => route('chat.show', $this->message->sender_id),
            'sender_id' => $this->message->sender_id,
        ];
    }

    /**
     * Fallback: wajib ada agar kompatibel dengan semua versi Laravel.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}