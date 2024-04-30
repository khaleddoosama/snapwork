<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Job extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = [];

    protected $casts = [
        'required_skills' => 'array',
        'attachments' => 'array',
    ];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }

    public function requestChanges()
    {
        return $this->hasMany(RequestChange::class, 'job_id');
    }
    
    // return hired application
    public function hiredApplication()
    {
        return $this->hasOne(Application::class, 'job_id')->where('status', 'hired');
    }
}
