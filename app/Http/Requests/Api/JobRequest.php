<?php

namespace App\Http\Requests\Api;

use App\Http\Controllers\Api\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class JobRequest extends FormRequest
{
    use ApiResponseTrait;
    public function authorize(): bool
    {
        return true;
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
            // 'attachments.*' => 'file',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['client_id'] = auth()->user()->id;

        return $data;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // throw new ValidationException($validator, response()->json($validator->errors(), 422));
        throw new ValidationException($validator, $this->apiResponse(null, $validator->errors(), 422));
    }
}
