<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RateRequest;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use App\Notifications\RateNotification;
use Illuminate\Http\Request;

class RateController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $rates = Rate::where('rated_by', auth()->user()->id)->get();
        return $this->apiResponse(RateResource::collection($rates), 'Rates fetched successfully', 200);
    }

    public function store(RateRequest $request)
    {
        $data = $request->validated();
        $rate = Rate::create($data);

        $user = $rate->rated_by;
        $user->notify(new RateNotification($rate));

        return $this->apiResponse(new RateResource($rate), 'Rate created successfully', 200);
    }

    public function show($id)
    {
        $rate = Rate::findOrFail($id);
        return $this->apiResponse(new RateResource($rate), 'Rate fetched successfully', 200);
    }
}
