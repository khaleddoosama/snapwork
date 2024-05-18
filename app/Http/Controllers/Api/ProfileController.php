<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CertificationRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\EducationRequest;
use App\Http\Requests\Api\EmploymentRequest;
use App\Http\Requests\Api\LanguageRequest;
use App\Http\Requests\Api\UpdateSkillsRequest;
use App\Http\Resources\CertificationResource;
use App\Http\Resources\EducationResource;
use App\Http\Resources\EmploymentResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\SpecializationResource;
use App\Http\Resources\UserResource;
use App\Models\Certification;
use App\Models\Education;
use App\Models\EmploymentHistory;
use App\Models\Language;
use App\Models\Skill;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['getSpecializations']]);
    }

    // change password
    public function changePassword(ChangePasswordRequest $request)
    {
        $data = $request->validated();

        $user = Auth::user();
        $user->update(['password' => bcrypt($data->password)]);


        return $this->apiResponse(new UserResource($user), 'Password changed successfully', 200);
    }

    //updateSkills
    public function updateSkills(UpdateSkillsRequest $request)
    {
        try {
           $data = $request->validated();

            $user = Auth::user();
            $skills = $request->skills;
            $existingSkills = $user->skills()->pluck('name')->toArray();

            $skillsToAdd = array_diff($skills, $existingSkills);
            $skillsToRemove = array_diff($existingSkills, $skills);

            DB::transaction(function () use ($user, $skillsToAdd, $skillsToRemove) {
                if ($skillsToRemove) {
                    $user->skills()->detach(Skill::whereIn('name', $skillsToRemove)->pluck('id'));
                }

                if ($skillsToAdd) {
                    foreach ($skillsToAdd as $skillName) {
                        $skill = Skill::firstOrCreate(['name' => $skillName]);
                        $user->skills()->attach($skill);
                    }
                }
            });

            return $this->apiResponse(new UserResource($user), 'Skills updated successfully', 200);
        } catch (Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 500);
        }
    }

    public function getSpecializations()
    {
        $specializations = Cache::remember('specializations', 3600, function () {
            return Specialization::all();
        });
        return $this->apiResponse(SpecializationResource::collection($specializations), 'Specializations fetched successfully', 200);
    }

    //getFreelancers
    public function getFreelancers($specialization_id = null)
    {
        $freelancers = User::freelancer()
            ->when($specialization_id, function ($query) use ($specialization_id) {
                return $query->where('specialization_id', $specialization_id);
            })
            ->latest()
            ->take(10)
            ->get();

        return $this->apiResponse(UserResource::collection($freelancers), 'Freelancers fetched successfully', 200);
    }
}
