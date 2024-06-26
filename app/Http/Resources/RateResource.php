<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
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
            'job' => new JobResource($this->job),
            'rating_by' => [
                'id' => $this->rating_by->id,
                'name' => $this->rating_by->name,
                'email' => $this->rating_by->email,
                'picture' => $this->rating_by->picture,
            ],
            'value' => $this->averageValue(),
            'comment' => $this->comment,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
