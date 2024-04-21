<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'client' => new UserResource($this->client),
            'title' => $this->title,
            'description' => $this->description,
            'required_skills' => $this->required_skills,
            'expected_budget' => $this->expected_budget,
            'expected_duration' => $this->expected_duration,
            'attachments' => $this->attachments,
            'created_at' => $this->created_at
        ];
    }
}
