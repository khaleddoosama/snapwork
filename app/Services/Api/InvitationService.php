<?php

namespace App\Services\Api;

use App\Models\Invitation;
use Illuminate\Validation\ValidationException;

class InvitationService
{


    // store invitation
    public function save(array $data)
    {
        $data['status'] = 'pending';
        return Invitation::create($data);
    }
}
