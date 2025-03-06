<?php

namespace VS\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use VS\Auth\Classes\TwoFA\OTPChannel;
use VS\Auth\Enums\OTPAction;
use VS\Base\Traits\HasRequestValidation;

class SendPinRequest extends FormRequest
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
            'channel' => ['required', Rule::enum(OTPChannel::class)],
            'action' => ['required', Rule::enum(OTPAction::class)]
        ];
    }
}
