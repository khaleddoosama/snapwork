<?php

namespace App\Models;

use App\Models\Scopes\OrderedDescScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected static function booted()
    {
        static::addGlobalScope(new OrderedDescScope);
    }
    protected $fillable = [
        'type', 'amount', 'user_id', 'escrow_id', 'status', 'paymob_order_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function escrow()
    {
        return $this->belongsTo(Escrow::class);
    }
}
