<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job' => $this->job->title,
            'client' => $this->job->client->name,
            'freelancer' => $this->freelancer->name,
            'status' => $this->status
        ];
    }
}
