<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }

}
