<?php

namespace VS\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use VS\Auth\Enums\TwoFAType;
use VS\Base\Traits\HasRequestValidation;

class OTPToggleRequest extends FormRequest
{
    use HasRequestValidation;
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
            'two_fa_type' => ['required', Rule::enum(TwoFAType::class)],
            'pin' => 'nullable|digits:4'
        ];
    }
}
