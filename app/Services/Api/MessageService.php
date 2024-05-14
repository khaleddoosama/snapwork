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


    // show all messages user
    public function showUserMessages($user_id)
    {
        $authUserId = auth()->id();
        $messages = Message::where(function ($query) use ($authUserId, $user_id) {
            $query->where('sender_id', $authUserId)->where('receiver_id', $user_id);
        })->orWhere(function ($query) use ($authUserId, $user_id) {
            $query->where('sender_id', $user_id)->where('receiver_id', $authUserId);
        })->get();

        return $messages;
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
