<?php

namespace App\Models;

use App\Models\Scopes\OrderedDescScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
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
                'source' => 'name'
            ]
        ];
    }
}
