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
}
