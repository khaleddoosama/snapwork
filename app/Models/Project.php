<?php

namespace App\Models;

use App\Models\Scopes\OrderedDescScope;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Project extends Model
{
    use HasFactory, UploadTrait;
    protected static function booted()
    {
        static::addGlobalScope(new OrderedDescScope);
    }
    protected $guarded = [];

    // json fields
    protected $casts = [
        'technologies' => 'array',
        'attachments' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }




    /* methods */
    // set Thumbnail Attribute
    public function setThumbnailAttribute(UploadedFile $thumbnail)
    {

        $folderName = $this->user_id . '/projects/' . $this->id;

        $this->deleteIfExists($this->thumbnail); // Delete the old image if it exists

        $this->attributes['thumbnail'] = $this->uploadImage($thumbnail, $folderName, 400, 400);
    }
}
