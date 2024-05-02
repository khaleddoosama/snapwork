<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MessageRequest;
use App\Http\Resources\MessageResource;
use App\Services\Api\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use ApiResponseTrait;

    private $messageService;
    //constructor
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index()
    {
        $messages = auth()->user()->messages;
        return $this->apiResponse(MessageResource::collection($messages), 'Messages fetched successfully', 200);
    }

    public function store(MessageRequest $request)
    {
        $data = $request->validated();

        $message = $this->messageService->save($data);
        return $this->apiResponse(new MessageResource($message), 'Message sent successfully', 200);
    }

    // mark as read

    public function markAsRead($message_id)
    {
        // dd($message);
        $message = $this->messageService->markAsRead($message_id);
        return $this->apiResponse(new MessageResource($message), 'Message marked as read successfully', 200);
    }
}
