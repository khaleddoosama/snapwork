<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BookmarkRequest;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    use ApiResponseTrait;

    private $bookmarkService;
    //constructor
    public function __construct(BookmarkService $bookmarkService)
    {
        $this->bookmarkService = $bookmarkService;
    }

    public function store(BookmarkRequest $request)
    {
        $data = $request->all();
        $bookmark = $this->bookmarkService->save($data);
        return $this->apiResponse(new BookmarkResource($bookmark), 'Bookmark created successfully', 200);
    }

}
