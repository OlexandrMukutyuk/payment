<?php

namespace App\Http\Requests\Api\Agent;

use Illuminate\Foundation\Http\FormRequest;
use Intervention\Validation\Rules\Creditcard;
use Intervention\Validation\Rules\Iban;

class AddCardRequest extends FormRequest
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
            'group_id' => ['required', 'exists:agents,group_id',],
            'chat_id' => ['required', 'exists:agents,chat_id'],
            'bank_id' => ['required', 'exists:banks,id'],
            'iban' => ['required', new Iban],
            'date_end' => [
                'required',
                'regex:/^(0[1-9]|1[0-2])\/\d{2}$/',
                function ($attribute, $value, $fail) {
                    $parts = explode('/', $value);
                    if (count($parts) !== 2) {
                        $fail("The $attribute format is invalid.");
                        return;
                    }
                    [$month, $year] = array_map('intval', $parts);
                    $year += 2000;
                    if ($year < date('Y') || ($year === (int)date('Y') && $month < (int)date('m'))) {
                        $fail("The $attribute must be a valid future date.");
                    }
                },
            ],
            'number' => ['required', new Creditcard ],
            'file' => ['required', 'file', 'mimes:jpeg,jpg,png,gif,pdf', 'max:4096'],
        ];
    }
}
