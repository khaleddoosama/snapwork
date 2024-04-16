<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmploymentResource extends JsonResource
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
            'company' => $this->company,
            'city' => $this->city,
            'country' => $this->country,
            'position' => $this->position,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'description' => $this->description
        ];
    }
}
