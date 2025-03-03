<?php

declare(strict_types=1);

namespace VS\Auth\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use VS\Base\Exceptions\APIException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use VS\Auth\Enums\OTPChannel;
use VS\Auth\Enums\OTPStatus;
use VS\Auth\Enums\OTPAction;
use VS\Auth\Mail\SendPin;
use VS\Auth\Models\OTP;


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

        // Create OTP
        $otp = OTP::create([
            'pin' => $pin,
            'otpable_id' => $otpable->id,
            'otpable_type' => get_class($otpable),
            'action' => $action,
            'status' => OTPStatus::VALID->name,
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


    public function verifyPin(Authenticatable $otpable, int $pin, string|OTPAction $action) : bool
    {
        if (!is_int($pin)) {
            throw new APIException('Invalid pin format.');
        }

        $action = is_string($action) ? OTPAction::tryFrom($action) : $action->value;
        if ($action) {
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
            $otp->status = OTPStatus::USED->value;
            $otp->save();
            return true;
        }
        return false;
    }

}
