<?php

namespace App\Models;

use App\Models\Scopes\OrderedDescScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory, Sluggable;
    protected static function booted()
    {
        static::addGlobalScope(new OrderedDescScope);
    }
    protected $guarded = [];

    public function sluggable(): array
    {

        return [
            'slug' => [
                'source' => ['job.slug', 'freelancer.id', 'freelancer.username'],
            ],
        ];
    }

    protected $casts = [
        'attachments' => 'array',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}
