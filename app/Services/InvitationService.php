<?php

namespace App\Services;

use App\Models\Invitation;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class InvitationService
{
    // get all invitations
    public function getAllInvitations()
    {
        // clear cache
        Cache::forget('invitations');
        $invitations = Cache::remember('invitations_page_' . request('page', 1), 60, function () {
            return Invitation::select('id', 'job_id', 'freelancer_id', 'status')
                ->with(['job', 'freelancer'])->paginate(1000);
        });
        return $invitations;
    }

    // get invitation by id
    public function getInvitationById($id)
    {
        $invitation = Invitation::where('id', $id)->with(['job', 'freelancer'])->first();
        return $invitation;
    }
}
