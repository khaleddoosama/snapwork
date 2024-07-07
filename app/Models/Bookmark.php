<?php

namespace App\Models;

use App\Models\Scopes\OrderedDescScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;
    protected static function booted()
    {
        static::addGlobalScope(new OrderedDescScope);
    }
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
