<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponseTrait;
    public function index(Request $request)
    {
        $notifications = auth()->user()->unreadNotifications;
        return $this->apiResponse($notifications, 'Notifications fetched successfully', 200);
    }


    public function read($id)
    {

        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();

        return $this->apiResponse([], 'Notification read successfully', 200);
    }
}
