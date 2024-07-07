<?php

namespace App\Models;

use App\Models\Scopes\OrderedDescScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escrow extends Model
{
    use HasFactory;
    protected static function booted()
    {
        static::addGlobalScope(new OrderedDescScope);
    }
    protected $fillable = [
        'job_id', 'amount', 'status'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
