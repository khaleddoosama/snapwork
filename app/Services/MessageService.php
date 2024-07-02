<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Rate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class MessageService
{
    // get all messages
    public function getAllMessages()
    {
        // clear cache
        Cache::forget('messages');
        $messages = Cache::remember('messages_page_' . request('page', 1), 60, function () {
            return Message::select('id', 'sender_id', 'receiver_id', 'content', 'status', 'sent_at', 'read_at')
                ->with(['sender', 'receiver'])->paginate(1000);
        });
        return $messages;
    }

    // get message by id
    public function getMessageById($id)
    {
        $message = Message::where('id', $id)->with(['sender', 'receiver'])->first();
        return $message;
    }
}
