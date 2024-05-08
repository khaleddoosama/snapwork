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
            'slug' => $this->slug,
            'freelancer' => new UserResource($this->freelancer),
            'bid' => $this->bid,
            'duration' => $this->duration,
            'cover_letter' => $this->cover_letter,
            'attachments' => $this->attachments,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'job' => [
                'id' => $this->job->id,
                'slug' => $this->job->slug,
                'client' => new UserResource($this->job->client),
                'specialization' => new SpecializationResource($this->job->specialization),
                'title' => $this->job->title,
                'description' => $this->job->description,
                'required_skills' => $this->job->required_skills,
                'expected_budget' => $this->job->expected_budget,
                'expected_duration' => $this->job->expected_duration,
                'attachments' => $this->job->attachments,
                'created_at' => $this->job->created_at,
                'status' => $this->job->status,
                'type' => $this->job->type,
                'location_type' => $this->job->location_type,
                'longitude' => $this->job->longitude,
                'latitude' => $this->job->latitude,
                'address' => $this->job->address,
                'reqeusts' => RequestChangeResource::collection($this->job->requestChanges),
            ],
        ];
    }
}
