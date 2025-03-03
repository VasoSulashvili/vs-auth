<?php

namespace VS\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use VS\Auth\Http\Requests\SendPinRequest;
use VS\Auth\Services\OTPService;

class OTPController extends Controller
{
    public function __construct(protected OTPService $otpService)
    {
    }
    public function send(SendPinRequest $request)
    {
        $otpService = new OTPService();

        $otpable = Auth::user();

        $otpService->send(
            otpable: $otpable,
            action: $request->input('action'),
            channel: $request->input('channel'));

        return response()->json(['message' => 'Pin sent successfully.']);
    }


}
