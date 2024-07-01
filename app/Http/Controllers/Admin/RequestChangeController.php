<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RequestChangeService;
use Illuminate\Http\Request;

class RequestChangeController extends Controller
{
    private $request_changeService;
    // constructor for request_changeService
    public function __construct(RequestChangeService $request_changeService)
    {
        $this->request_changeService = $request_changeService;
    }

    // index
    public function index()
    {
        $request_changes = $this->request_changeService->getAllRequestChanges();
        $title = __('attributes.request_changes');
        return view('admin.request_change.index', compact('title', 'request_changes'));
    }

    // show
    public function show($id)
    {
        $request_change = $this->request_changeService->getRequestChangeById($id);
        $title = __('attributes.request_change');
        return view('admin.request_change.show', compact('title', 'request_change'));
    }
}
