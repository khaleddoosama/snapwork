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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        $this->middleware('jwt.verify');
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
        $user->password = bcrypt($request->password);
        $user->save();

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
            $user_skills = $user->skills()->pluck('name')->toArray();

            $diff_skills = array_diff($skills, $user_skills);

            // remove unselected skill
            $user->skills()->detach($user->skills->whereNotIn('name', $skills)->pluck('id')->toArray());

            if (count($diff_skills) > 0) {
                foreach ($diff_skills as $skill) {
                    $skill_exist = Skill::where('name', $skill)->first();

                    if (!$skill_exist) {
                        $skill_exist = Skill::create(['name' => $skill]);
                    }

                    $user->skills()->attach($skill_exist);
                }
            }

            return $this->apiResponse(new UserResource($user), 'Skills updated successfully', 200);
        } catch (Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 500);
        }
    }

    // update languages
    public function updateLanguages(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'languages' => 'required|array',
                'languages.*.name' => 'required|string',
                'languages.*.level' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, $validator->errors()->first(), 422);
            }

            $user = Auth::user();
            $newLanguages = collect($request->languages);  // Assuming this is an array of ['name' => '...', 'level' => '...']
            $currentLanguages = $user->languages()->get(['name', 'level'])->keyBy('name');

            // Loop through the new languages and compare with current
            foreach ($newLanguages as $language) {
                $user->languages()->updateOrCreate(['name' => $language['name']], ['level' => $language['level']]);
            }

            // Remove any languages that are not in the new list
            foreach ($currentLanguages as $currentLanguage) {
                if (!$newLanguages->contains('name', $currentLanguage->name)) {
                    $user->languages()->where('name', $currentLanguage->name)->delete();
                }
            }

            return $this->apiResponse(new UserResource($user), 'Languages updated successfully', 200);
        } catch (Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 500);
        }
    }

    // update educations
    public function updateEducations(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'educations' => 'required|array',
                'educations.*.school' => 'required|string',
                'educations.*.degree' => 'required|string',
                'educations.*.start_date' => 'required|date',
                'educations.*.end_date' => 'required|date',
                'educations.*.major' => 'nullable|string',
                'educations.*.description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, $validator->errors()->first(), 422);
            }

            $user = Auth::user();
            // Update existing educations or create new ones
            foreach ($request->educations as $educationData) {
                $employment = $user->educations()->updateOrCreate(
                    ['id' => $educationData['id'] ?? null], // Assuming 'id' is a unique identifier for educations
                    $educationData
                );
            }

            // Remove any educations that are not in the new list
            $user->educations()->whereNotIn('id', collect($request->educations)->pluck('id'))->delete();

            return $this->apiResponse(new UserResource($user), 'Educations updated successfully', 200);
        } catch (Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 500);
        }
    }

    //employments
    public function updateEmployments(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employments' => 'required|array',
                'employments.*.company' => 'required|string',
                'employments.*.position' => 'required|string',
                'employments.*.city' => 'nullable|string',
                'employments.*.country' => 'nullable|string',
                'employments.*.start_date' => 'required|date',
                'employments.*.end_date' => 'required|date',
                'employments.*.description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, $validator->errors()->first(), 422);
            }

            $user = Auth::user();

            $arr = [];
            // Update existing projects or create new ones
            foreach ($request->employments as $employmentData) {
                $employment = $user->employments()->updateOrCreate(
                    ['id' => $employmentData['id'] ?? null], // Assuming 'id' is a unique identifier for employments
                    $employmentData
                );
                $arr[] = $employment->id;
            }

            // Remove any projects that are not in the new list
            $user->employments()->whereNotIn('id', $arr)->delete();

            return $this->apiResponse(new UserResource($user), 'Employments updated successfully', 200);
        } catch (Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 500);
        } catch (Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 500);
        }
    }

    // update projects
    // public function updateProjects(Request $request)
    // {

    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'projects' => 'required|array',
    //             'projects.*.title' => 'required|string',
    //             'projects.*.description' => 'required|string',
    //             'projects.*.url' => 'nullable|url',
    //             'projects.*.technologies' => 'nullable|array',
    //             'projects.*.completion_date' => 'nullable|date',
    //             'projects.*.thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //             'projects.*.attachments' => 'nullable|array',
    //         ]);
    //         if ($validator->fails()) {
    //             return $this->apiResponse(null, $validator->errors()->first(), 422);
    //         }

    //         $user = Auth::user();

    //         // Update existing projects or create new ones
    //         foreach ($request->projects as $projectData) {
    //             $project = $user->projects()->updateOrCreate(
    //                 ['id' => $projectData['id'] ?? null], // Assuming 'id' is a unique identifier for projects
    //                 $projectData
    //             );
    //         }

    //         return $this->apiResponse(new UserResource($user), 'Projects updated successfully', 200);
    //     } catch (Exception $e) {
    //         return $this->apiResponse(null, $e->getMessage(), 500);
    //     }
    // }

    // update certifications
    public function updateCertifications(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'certifications' => 'required|array',
                'certifications.*.name' => 'required|string',
                'certifications.*.issuer' => 'required|string',
                'certifications.*.issue_date' => 'required|date',
                'certifications.*.url' => 'nullable|url',
                'certifications.*.description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, $validator->errors()->first(), 422);
            }

            $user = Auth::user();
            // remove old certifications
            $user->certifications()->delete();

            // add new certifications
            $user->certifications()->createMany($request->certifications);

            return $this->apiResponse(new UserResource($user), 'Certifications updated successfully', 200);
        } catch (Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 500);
        }
    }

    public function getSpecializations()
    {
        $specializations = Specialization::get();
        return $this->apiResponse(SpecializationResource::collection($specializations), 'Specializations fetched successfully', 200);
    }
}
