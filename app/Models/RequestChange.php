<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestChange extends Model
{
    use HasFactory;

    protected $guarded = [];


    // when change status put time now in response_at
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;
        $this->attributes['response_at'] = now();
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
