<?php

declare(strict_types=1);

namespace VS\Auth\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use VS\Auth\Classes\TwoFA\OTPChannel;
use VS\Auth\Classes\TwoFA\OTPStatus;
use VS\Auth\Enums\OTPAction;
use VS\Auth\Enums\TwoFAType;
use VS\Auth\Mail\SendPin;
use VS\Auth\Models\OTP;
use VS\Base\Exceptions\APIException;


class OTPService
{
    public function generatePin(): int
    {
        return mt_rand(1000, 9999);
    }


    public function create($otpable, int $pin, string|OTPAction $action) : bool
    {
        if (!filter_var($otpable->email, FILTER_VALIDATE_EMAIL)) {
            throw new APIException('Invalid email format.');
        }

        $action = is_string($action) ? OTPAction::tryFrom($action) : $action->value;

        if (!$action) {
            throw new APIException('Invalid action.');
        }

        // Deactivate all previous same OTPs
        OTP::where('otpable_id', $otpable->id)
            ->where('otpable_type', get_class($otpable))
            ->where('action', $action)
            ->update(['status' => OTPStatus::UNVERIFIED->value]);

        // Create OTP
        $otp = OTP::create([
            'pin' => $pin,
            'otpable_id' => $otpable->id,
            'otpable_type' => get_class($otpable),
            'action' => $action,
            'status' => OTPStatus::VALID->value,
            'expires_at' => now()->addMinutes(5)
        ]);

        if (!$otp) {
            throw new APIException('Failed to create OTP.');
        }
        return true;
    }



    public function send(Model $otpable, string|OTPAction $action, string|OTPChannel $channel) : bool
    {
        DB::beginTransaction();

        // Generate pin
        $pin = $this->generatePin();

        // Create OTP
        $this->create($otpable, $pin, $action);

        if ($channel === OTPChannel::EMAIL || OTPChannel::tryFrom($channel) === OTPChannel::EMAIL) {
            $status = $this->sendPinViEmail($pin, $otpable->email);
        } else {
            throw new APIException('Invalid channel.');
        }

        if ($status) {
            DB::commit();
            return true;
        } else {
            DB::rollBack();
            return false;
        }
    }

    public function sendPinViEmail(int $pin, string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new APIException('Invalid email format.');
        }

        if (!filter_var($pin, FILTER_VALIDATE_INT)) {
            throw new APIException('Invalid pin format.');
        }

        // Send email
        return Mail::to($email)->send(new SendPin($pin));

    }


    public function toggleTwoFa(Model $otpable, string|TwoFAType $twoFAType, int|null $pin = null) : bool
    {
        if (!in_array('two_fa_type', $otpable->getFillable())) {
            throw new APIException('Invalid model.');
        }

        if (is_string($twoFAType) && TwoFAType::tryFrom($twoFAType)) {
            $twoFAType = TwoFAType::tryFrom($twoFAType);
        } else {
            throw new APIException('Invalid two factor authentication type.');
        }

        // IF 2FA is already enabled, disable it
        if ($twoFAType === TwoFAType::NONE) {
            $otpable->two_fa_type = TwoFAType::NONE->value;
            return $otpable->save();
        } else {
            // IF 2FA is not enabled, enable it
            if (!$pin || !is_int($pin)) {
                throw new APIException('Pin is required.');
            } else {
                // Verify pin
                if ($this->verifyPin($otpable, $pin, OTPAction::ENABLE_2FA)) {
                    $otpable->two_fa_type = $twoFAType->value;
                    return $otpable->save();
                } else {
                    throw new APIException('Invalid pin.');
                }
            }
        }
    }



    public function verifyPin(Authenticatable $otpable, int $pin, string|OTPAction $action) : bool
    {
        if (!is_int($pin)) {
            throw new APIException('Invalid pin format.');
        }

        $action = is_string($action) ? OTPAction::tryFrom($action) : $action->value;
        if (!$action) {
            throw new APIException('Invalid action.');
        }


        $otp = OTP::where('otpable_id', $otpable->id)
            ->where('otpable_type', get_class($otpable))
            ->where('pin', $pin)
            ->where('action', $action)
            ->where('status', OTPStatus::VALID->name)
            ->where('expires_at', '>', now())
            ->first();

        if ($otp) {
            $otp->status = OTPStatus::VERIFIED->value;
            $otp->save();
            return true;
        }
        return false;
    }

}
