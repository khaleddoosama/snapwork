<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\InvitationService;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    private $invitationService;
    // constructor for invitationService
    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    // index
    public function index()
    {
        $invitations = $this->invitationService->getAllInvitations();
        $title = __('attributes.invitations');
        return view('admin.invitation.index', compact('title', 'invitations'));
    }

    // show
    public function show($id)
    {
        $invitation = $this->invitationService->getInvitationById($id);
        $title = __('attributes.invitation');
        return view('admin.invitation.show', compact('title', 'invitation'));
    }
}
