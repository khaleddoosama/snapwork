<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Abstract\AbstractProfileController;
use App\Http\Requests\Api\LanguageRequest;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;

class LanguageController extends AbstractProfileController
{
    public function addLanguage(LanguageRequest $request)
    {
        return $this->addUserData($request, 'languages', 'Language added successfully');
    }

    public function updateLanguage(LanguageRequest $request, Language $language)
    {
        return $this->updateUserData($request, $language, LanguageResource::class, 'Language updated successfully');
    }

    public function deleteLanguage(Language $language)
    {
        return $this->deleteUserData($language, 'Language deleted successfully');
    }
}
