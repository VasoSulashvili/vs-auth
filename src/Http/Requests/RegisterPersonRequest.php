<?php

namespace VSAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use VSApi\Traits\HasAPIValidation;

class RegisterPersonRequest extends FormRequest
{
    use HasAPIValidation;
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
            'email' => 'required|string|email|max:255|unique:people',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
