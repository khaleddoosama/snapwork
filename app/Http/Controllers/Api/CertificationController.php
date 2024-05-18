<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Abstract\AbstractProfileController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CertificationRequest;
use App\Http\Resources\CertificationResource;
use App\Models\Certification;
use Illuminate\Http\Request;

class CertificationController extends AbstractProfileController
{
    public function addCertification(CertificationRequest $request)
    {
        return $this->addUserData($request, 'certifications', 'Certification added successfully');
    }

    public function updateCertification(CertificationRequest $request, Certification $certification)
    {
        return $this->updateUserData($request, $certification, CertificationResource::class, 'Certification updated successfully');
    }

    public function deleteCertification(Certification $certification)
    {
        return $this->deleteUserData($certification, 'Certification deleted successfully');
    }
}
