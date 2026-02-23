<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewChatNotification extends Notification
{
    use Queueable;

    protected $message;

    // 1. Terima data pesan dari Controller
    public function __construct($message)
    {
        $this->message = $message;
    }

    // 2. Ubah 'mail' jadi 'database'
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // 3. Simpan data ke tabel notifications
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'chat',
            'title' => 'Pesan Baru dari ' . $this->message->sender->name,
            'message' => Str::limit($this->message->message, 50), // Potong pesan kalau kepanjangan
            'url' => route('chat.show', $this->message->sender_id),
            'sender_id' => $this->message->sender_id,
        ];
    }
}