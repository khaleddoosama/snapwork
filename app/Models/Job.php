<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'required_skills' => 'array',
        'attachments' => 'array',
    ];


    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
