<?php

namespace App\Http\Requests\Api\Agent;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'group_id' => ['required', 'string', 'unique:agents,group_id', 'max:100'],
            'chat_id' => ['required', 'string', 'unique:agents,chat_id', 'max:100'],
            'phone' => ['required', 'string', 'max:40'],
            'name' => ['required', 'string', 'max:250'],
            'is_one_day' => ['required', 'nullable'],
            'active' => ['required', 'nullable'],
            'schedule' => ['required', 'string', 'max:250'],
            'inn' => ['required', 'string', 'unique:agents,inn', 'max:12', 'min:10'],
        ];
    }
}
