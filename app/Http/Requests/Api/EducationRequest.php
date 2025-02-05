<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class EducationRequest extends FormRequest
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
            'school' => 'required|string',
            'degree' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'major' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }
}
