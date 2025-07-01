<?php

namespace App\Http\Requests\Api\OutgoingPayment;

use Illuminate\Foundation\Http\FormRequest;
use Intervention\Validation\Rules\Creditcard;
use Intervention\Validation\Rules\Iban;

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
            'fee' => ['required', 'integer', 'min:0'],
            'incoming_sum' => ['required', 'integer', 'min:0'],
        ];
    }
}
