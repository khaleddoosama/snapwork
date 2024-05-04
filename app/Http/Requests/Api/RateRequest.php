<?php

namespace App\Http\Requests\Api;

use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest;

class RateRequest extends FormRequest
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
            // job_id with rating_by with rated_by must be unique
            'job_id' => 'required|exists:jobs,id|unique:rates,job_id,NULL,id,rating_by,' . auth()->user()->id . ',rated_by,' . request()->input('rated_by'),
            'rating_by' => 'exists:users,id',
            'rated_by' => 'required|exists:users,id',
            'rates' => 'required|array',
            'rates.*.name' => 'required|string',
            'rates.*.value' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['rating_by'] = auth()->user()->id;

        return $data;
    }
}
