<?php

declare(strict_types=1);

namespace VS\Auth\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use VS\Base\Classes\API;
use VS\Base\Exceptions\APIException;
use Illuminate\Auth\Events\Verified;


abstract class EmailVerificationController extends Controller
{
    protected $guard = null;

    // Handle email verification
    public function verify(Request $request, $id, $hash)
    {
        // Get model from guard
        $provider = config('auth.guards.' . $this->guard . '.provider');
        $user = config('auth.providers.' . $provider . '.model')::find($id);

        // Check if user exists
        if (!$user) {
            throw new APIException('User not found.', 404);
        }

        // Check if the hash matches the user's email
        if (sha1($user->getEmailForVerification()) !== $hash) {
            throw new APIException('Invalid verification link.', 403);
        }

        // If already verified
        if ($user->hasVerifiedEmail()) {
            throw new APIException('Email already verified.', 403);
        }

        // Mark as verified
        $user->markEmailAsVerified();

        event(new Verified($user));

        return API::response(status: true, message: 'Email verified successfully.', code: 200, );
    }



    // Resend verification email
    public function resend(Request $request)
    {
        if ($request->user($this->guard)->hasVerifiedEmail()) {
            throw new APIException('Email already verified.', 403);
        }

        $request->user($this->guard)->sendEmailVerificationNotification();

        return API::response(status: true, message: 'Verification link sent.', code: 200);
    }
}
