<?php

namespace VS\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use VS\Auth\Enums\TwoFAType;
use VS\Auth\Http\Requests\OTPToggleRequest;
use VS\Auth\Http\Requests\SendPinRequest;
use VS\Auth\Services\OTPService;
use VS\Base\Classes\API;

class OTPController extends Controller
{
    public function __construct(protected OTPService $otpService)
    {
    }
    public function send(SendPinRequest $request)
    {

        $otpable = Auth::user();

        $this->otpService->send(
            otpable: $otpable,
            action: $request->input('action'),
            channel: $request->input('channel'));

        return API::response(message: 'OTP sent successfully.');
    }

    public function toggleTwoFA(OTPToggleRequest $request)
    {
        try {
            $otpable = Auth::user();
            $this->otpService->toggleTwoFa(
                otpable: $otpable,
                twoFAType: $request->input('two_fa_type'),
                pin: $request->input('pin'));

        } catch (\Exception $e) {
            return API::response(message: $e->getMessage(), status: 400);
        }


        return API::response(message: 'Two Factor Authentication toggled successfully.');
    }



}
