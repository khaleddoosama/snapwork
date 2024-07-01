<?php

namespace App\Services;

use App\Models\Rate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class RateService
{
    // get all rates
    public function getAllRates()
    {
        // clear cache
        Cache::forget('rates');
        $rates = Cache::remember('rates_page_' . request('page', 1), 60, function () {
            return Rate::select('id', 'job_id', 'rated_by', 'rating_by', 'comment', 'rates')
                ->with(['job', 'ratedBy', 'ratingBy'])->paginate(1000);
        });
        return $rates;
    }

    // get rate by id
    public function getRateById($id)
    {
        $rate = Rate::where('id', $id)->with(['job', 'ratedBy', 'ratingBy'])->first();
        return $rate;
    }
}
