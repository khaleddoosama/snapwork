<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class EmploymentRequest extends FormRequest
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
            'company' => 'required|string',
            'position' => 'required|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'nullable|string',
        ];
    }
}
