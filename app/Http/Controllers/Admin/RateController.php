<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RateService;
use Illuminate\Http\Request;

class RateController extends Controller
{
    private $rateService;
    // constructor for rateService
    public function __construct(RateService $rateService)
    {
        $this->rateService = $rateService;
    }

    // index
    public function index()
    {
        $rates = $this->rateService->getAllRates();
        $title = __('attributes.rates');
        return view('admin.rate.index', compact('title', 'rates'));
    }

    // show
    public function show($id)
    {
        $rate = $this->rateService->getRateById($id);
        $title = __('attributes.rate');
        return view('admin.rate.show', compact('title', 'rate'));
    }
}
