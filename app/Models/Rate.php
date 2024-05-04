<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $guarded = [];

    // cast
    protected $casts = [
        'rates' => 'array',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function ratedBy()
    {
        return $this->belongsTo(User::class, 'rated_by');
    }

    public function ratingBy()
    {
        return $this->belongsTo(User::class, 'rating_by');
    }

    // return average rate
    public function averageValue()
    {
        $values = array_column($this->rates, 'value'); // Extract 'value' fields into a separate array

        // Calculate the average
        $average = count($values) > 0 ? array_sum($values) / count($values) : 0;

        // Round the average to 2 decimal place
        return round($average, 2);
    }
}
