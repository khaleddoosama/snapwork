<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bid' => ['required', 'numeric'],
            'duration' => ['required', 'integer'],
            'cover_letter' => ['required', 'string'],
            'attachments' => ['nullable', 'array'],
            // job_id, freelancer_id must be unique
            'job_id' => 'required|exists:jobs,id|unique:applications,job_id,NULL,id,freelancer_id,' . auth()->user()->id,
            'freelancer_id' => 'exists:users,id,role,freelancer',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['freelancer_id'] = auth()->user()->id;

        return $data;
    }
}
