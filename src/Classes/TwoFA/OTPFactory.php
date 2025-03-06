<?php

declare(strict_types=1);

namespace VS\Auth\Classes\TwoFA;

use Illuminate\Contracts\Auth\Authenticatable;
use VS\Base\Exceptions\APIException;
use VS\Auth\Models\OTP;

class OTPFactory implements OTPInterface
{
    protected $channel;
    protected Authenticatable $user;
    protected int $pin;
    protected OTP $otp;


    public function __construct(string $channel, Authenticatable $user)
    {
        if (OTPChannel::tryFrom($channel) === null) {
            throw new APIException('Invalid channel');
        }

        $this->channel = OTPChannel::tryFrom($channel)->getChannel();
        $this->user = $user;
    }


    /**
     * @param int $pin
     * @return bool
     * @throws APIException
     */
    public function verify(int $pin): bool
    {
        if ($this->user->latestPin->pin !== $pin) {
            throw new APIException('Invalid pin');
        }

        return true;
    }


    /**
     * Send OTP to the user
     *
     * @return bool
     * @throws APIException
     */
    public function send(): bool
    {
        // Setup OTP
        $this->setup();

        // Send email
        $this->channel->send();

        return true;
    }


    /**
     * Setup OTP
     *
     * @return bool
     * @throws APIException
     */
    protected function setup(): true
    {
        // generate pin
        $this->generatePin();

        // create OTP
        $this->createOTP();

        return true;
    }


    /**
     * Disables old unused pins and Creates new OTP for the user
     *
     * @return bool
     * @throws APIException
     */
    protected function createOTP(): bool
    {
        // Deactivate all previous expired
        OTP::where('otpable_id', $this->user->id)
            ->where('otpable_type', get_class($this->user))
            ->where('status', OTPStatus::VALID->value)
            ->update(['status' => OTPStatus::DISABLED->value]);

        $otp = OTP::create([
            'otpable_id' => $this->user->id,
            'otpable_type' => get_class($this->user),
            'channel' => $this->channel,
            'pin' => $this->pin,
            'expires_at' => now()->addMinutes(5)
        ]);

        if ($otp) {
            return true;
        } else {
            throw new APIException('Failed to create OTP');
        }
    }


    /**
     * Generate pin and set it to $this->pin
     *
     * @param int $min
     * @param int $max
     * @return void
     * @throws APIException
     */
    protected function generatePin(int $min = 999, int $max = 9999): void
    {
        if ($min >= $max) {
            throw new APIException('Min value should be less than max value');
        }
        $this->pin = rand(100000, 999999);
    }

}
