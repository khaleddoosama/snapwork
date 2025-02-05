<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file'
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['sender_id'] = auth()->user()->id;

        return $data;
    }
}
