<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'freelancer' => $this->freelancer->name,
            'bid' => $this->bid,
            'duration' => $this->duration,
            'cover_letter' => $this->cover_letter,
            'attachments' => $this->attachments,
            'created_at' => $this->created_at,
        ];
    }
}
