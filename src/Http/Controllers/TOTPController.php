<?php

namespace VS\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use VS\Auth\Services\TOTPService;
use VS\Base\Classes\API;


class TOTPController extends Controller
{
    public function __construct(protected TOTPService $totpService)
    {
    }
    public function enable()
    {

        $user = Auth::user();

        $code = $this->totpService->enable($user);

        return API::response(
            data: ['secret' => $code['secret'], 'qr_code_url' => $code['qr_code_url']],
            message: 'TOTP enabled successfully.');
    }




}
