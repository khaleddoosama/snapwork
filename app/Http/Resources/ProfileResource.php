<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'user' => new UserResource($this),
                'skills' => SkillResource::collection($this->skills),
                'languages' => LanguageResource::collection($this->languages),
                'projects' => ProjectResource::collection($this->projects),
                'educations' => EducationResource::collection($this->educations),
                'Employment' => EmploymentResource::collection($this->employments),
                'certifications' => CertificationResource::collection($this->certifications),
                'rates' => RateResource::collection($this->rates),
            ];
    }
}
