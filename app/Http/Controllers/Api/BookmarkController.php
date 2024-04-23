<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BookmarkRequest;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use App\Services\Api\BookmarkService;
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


    public function index()
    {
        // get bookmarks for current user
        $bookmarks = $this->bookmarkService->get();
        return $this->apiResponse(BookmarkResource::collection($bookmarks), 'Bookmarks fetched successfully', 200);
    }

    public function store(BookmarkRequest $request)
    {
        $data = $request->validated();
        $bookmark = $this->bookmarkService->save($data);
        return $this->apiResponse(new BookmarkResource($bookmark), 'Bookmark created successfully', 201);
    }


    public function destroy(Bookmark $bookmark)
    {
        // check authorization
        if ($bookmark->user_id != auth()->user()->id) {
            return $this->apiResponse(null, 'Unauthorized', 401);
        }
        $bookmark->delete();

        return $this->apiResponse(null, 'Bookmark deleted successfully', 200);
    }
}
