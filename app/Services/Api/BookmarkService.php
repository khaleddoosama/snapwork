<?php

namespace App\Services\Api;

use App\Models\Application;
use App\Models\Bookmark;
use Illuminate\Validation\ValidationException;

class BookmarkService
{


    public function get()
    {
        $bookmarks = Bookmark::where('user_id', auth()->user()->id)->get();
        return $bookmarks;
    }

    // store application
    public function save(array $data)
    {
        $bookmark = Bookmark::where('job_id', $data['job_id'])->where('user_id', auth()->user()->id)->first();
        if (!$bookmark) {
            $bookmark = Bookmark::create($data);
        } else {
            $bookmark->update($data);
        }
        return $bookmark;
    }
}
