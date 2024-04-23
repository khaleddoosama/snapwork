<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\InvitationRequest;
use App\Http\Resources\InvitationResource;
use App\Services\Api\InvitationService;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    use ApiResponseTrait;
    protected $invitationService;
    // constructor
    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }



    public function store(InvitationRequest $request)
    {
        $data = $request->validated();
        $inivitation = $this->invitationService->save($data);
        return $this->apiResponse(new InvitationResource($inivitation), 'Invitation sent successfully', 201);
    }
}
