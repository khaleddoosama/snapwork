<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BookmarkRequest extends FormRequest
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
            // job_id, user_id must be unique
            'job_id' => 'required|exists:jobs,id|unique:bookmarks,job_id,NULL,id,user_id,' . auth()->user()->id,
            'user_id' => 'exists:users,id'
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['user_id'] = auth()->user()->id;

        return $data;
    }
}
