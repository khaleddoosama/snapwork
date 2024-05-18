<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Abstract\AbstractProfileController;
use App\Http\Requests\Api\EmploymentRequest;
use App\Http\Resources\EmploymentResource;
use App\Models\EmploymentHistory;

class EmploymentController extends AbstractProfileController
{
    public function addEmployment(EmploymentRequest $request)
    {
        return $this->addUserData($request, 'employments', 'Employment added successfully');
    }

    public function updateEmployment(EmploymentRequest $request, EmploymentHistory $employment)
    {
        return $this->updateUserData($request, $employment, EmploymentResource::class, 'Employment updated successfully');
    }

    public function deleteEmployment(EmploymentHistory $employment)
    {
        return $this->deleteUserData($employment, 'Employment deleted successfully');
    }
}
