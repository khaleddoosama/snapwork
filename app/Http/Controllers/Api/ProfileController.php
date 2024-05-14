<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpecializationResource;
use App\Http\Resources\UserResource;
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
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|current_password',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors()->first(), 422);
        }

        $user = Auth::user();
        $user->update(['password' => bcrypt($request->password)]);


        return $this->apiResponse(new UserResource($user), 'Password changed successfully', 200);
    }

    //updateSkills
    public function updateSkills(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'skills' => 'required|array',
                'skills.*' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, $validator->errors()->first(), 422);
            }

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

    private function updateUserData($request, $rules, $relation, $resourceMessage)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors()->first(), 422);
        }

        $user = Auth::user();
        $data = $request->all();

        DB::transaction(function () use ($user, $data, $relation, &$ids) {
            $ids = [];
            foreach ($data[$relation] as $item) {

                $model = $user->$relation()->updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    $item
                );
                $ids[] = $model->id;
            }

            $user->$relation()->whereNotIn('id', $ids)->delete();
        });

        return $this->apiResponse(new UserResource($user), $resourceMessage, 200);
    }

    public function updateLanguages(Request $request)
    {
        $rules = [
            'languages' => 'required|array',
            'languages.*.name' => 'required|string',
            'languages.*.level' => 'required',
        ];

        return $this->updateUserData($request, $rules, 'languages', 'Languages updated successfully');
    }

    public function updateEducations(Request $request)
    {
        $rules = [
            'educations' => 'required|array',
            'educations.*.school' => 'required|string',
            'educations.*.degree' => 'required|string',
            'educations.*.start_date' => 'required|date',
            'educations.*.end_date' => 'required|date',
            'educations.*.major' => 'nullable|string',
            'educations.*.description' => 'nullable|string',
        ];

        return $this->updateUserData($request, $rules, 'educations', 'Educations updated successfully');
    }

    public function updateEmployments(Request $request)
    {
        $rules = [
            'employments' => 'required|array',
            'employments.*.company' => 'required|string',
            'employments.*.position' => 'required|string',
            'employments.*.city' => 'nullable|string',
            'employments.*.country' => 'nullable|string',
            'employments.*.start_date' => 'required|date',
            'employments.*.end_date' => 'required|date',
            'employments.*.description' => 'nullable|string',
        ];

        return $this->updateUserData($request, $rules, 'employments', 'Employments updated successfully');
    }

    public function updateCertifications(Request $request)
    {
        $rules = [
            'certifications' => 'required|array',
            'certifications.*.name' => 'required|string',
            'certifications.*.issuer' => 'required|string',
            'certifications.*.issue_date' => 'required|date',
            'certifications.*.url' => 'nullable|url',
            'certifications.*.description' => 'nullable|string',
        ];

        return $this->updateUserData($request, $rules, 'certifications', 'Certifications updated successfully');
    }


    public function getSpecializations()
    {
        $specializations = Cache::remember('specializations', 60, function () {
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
