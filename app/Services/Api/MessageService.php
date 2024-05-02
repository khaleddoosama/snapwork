<?php

namespace App\Services\Api;

use App\Models\Message;
use Illuminate\Validation\ValidationException;

class MessageService
{

    // store Message
    public function save(array $data): Message
    {
        $message = Message::create($data);
        return $message;
    }


    public function markAsRead($message_id)
    {
        $message = Message::findOrFail($message_id);
        $message->update(
            [
                'status' => 'read',
                'read_at' => now()
            ]
        );
        return $message;
    }
}
