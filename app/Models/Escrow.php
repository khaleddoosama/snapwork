<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id', 'amount', 'status', 'transaction_id'
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
