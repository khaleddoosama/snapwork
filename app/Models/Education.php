<?php

namespace App\Models;

use App\Models\Scopes\OrderedDescScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected static function booted()
    {
        static::addGlobalScope(new OrderedDescScope);
    }
    protected $guarded = [];
}
