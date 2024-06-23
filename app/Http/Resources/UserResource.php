<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'slug' => $this->slug,
            'bio' => $this->bio,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'picture' => $this->picture,
            'cover' => $this->cover,
            'video' => $this->video,
            'phone' => $this->phone,
            'phone_verified_at' => $this->phone_verified_at,
            'country' => $this->country,
            'address' => $this->address,
            'role' => $this->role,
            'specialization' => $this->specialization ? $this->specialization->name : null,
            'job_title' => $this->job_title,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'balance' => $this->balance,
            'status' => $this->status,
            'skills' => SkillResource::collection($this->skills),
            'languages' => LanguageResource::collection($this->languages),
            'projects' => ProjectResource::collection($this->projects),
            'educations' => EducationResource::collection($this->educations),
            'Employment' => EmploymentResource::collection($this->employments),
            'certifications' => CertificationResource::collection($this->certifications),
            'rates' => RateResource::collection($this->rates),
            'created_at' => $this->created_at,
        ];
    }
}
