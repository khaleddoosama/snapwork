<?php

namespace App\Http\Requests\Api;

use App\Models\RequestChange;
use Illuminate\Foundation\Http\FormRequest;

class RequestChangeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user()->can('create', [RequestChange::class, $this->route('job')]);
    }


    public function rules(): array
    {
        return [
            'type' => 'required|string|max:50',
            'new_bid' => 'nullable|string|max:50',
            'new_duration' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'freelancer_id' => 'exists:users,id',
        ];
    }
}
