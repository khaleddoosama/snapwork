<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Abstract\AbstractProfileController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EducationRequest;
use App\Http\Resources\EducationResource;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends AbstractProfileController
{
    public function addEducation(EducationRequest $request)
    {

        return $this->addUserData($request, 'educations', 'Education added successfully');
    }

    public function updateEducation(EducationRequest $request, Education $education)
    {
        return $this->updateUserData($request, $education, EducationResource::class, 'Education updated successfully');
    }

    public function deleteEducation(Education $education)
    {
        return $this->deleteUserData($education, 'Education deleted successfully');
    }
}
