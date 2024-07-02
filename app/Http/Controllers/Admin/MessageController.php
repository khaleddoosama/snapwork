<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private $messageService;
    // constructor for messageService
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    // index
    public function index()
    {
        $messages = $this->messageService->getAllMessages();
        $title = __('attributes.messages');
        return view('admin.message.index', compact('title', 'messages'));
    }

    // show
    public function show($id)
    {
        $message = $this->messageService->getMessageById($id);
        $title = __('attributes.message');
        return view('admin.message.show', compact('title', 'message'));
    }
}
