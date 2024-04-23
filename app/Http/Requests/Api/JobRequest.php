<?php

namespace App\Http\Requests\Api;

use App\Exceptions\CustomAuthorizationException;
use App\Http\Controllers\Api\ApiResponseTrait;
use Flasher\Laravel\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JobRequest extends FormRequest
{
    use ApiResponseTrait;
    public function authorize(): bool
    {
        // if method is post, then must check the user is logged in
        // if method is put, then must check the user is logged in and the user is the owner of the job
        if ($this->isMethod('post')) {
            return true;
        } else if ($this->isMethod('put')) {
            return auth()->check() && auth()->user()->id == $this->job->client_id;
        }

        return false;
    }


    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'required_skills' => ['required', 'array'],
            'expected_budget' => ['required', 'numeric'],
            'expected_duration' => ['required', 'integer'],
            'attachments' => ['nullable', 'array'],
            'client_id' => 'exists:users,id',
            'specialization_id' => 'exists:specializations,id',
            // 'attachments.*' => 'file',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['client_id'] = auth()->user()->id;

        return $data;
    }
}
