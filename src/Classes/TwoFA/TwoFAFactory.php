<?php

namespace VS\Auth\Classes\TwoFA;

use Illuminate\Contracts\Auth\Authenticatable;
use VS\Base\Exceptions\APIException;

final class TwoFAFactory implements TwoFAInterface
{
    protected $channel;

    protected Authenticatable $user;

//    protected \ReflectionClass $reflection;

    public function __construct(string $type, Authenticatable $user)
    {
        $this->channel = match ($type) {
            'totp' =>  new TOTPFactory(),
            'email', 'sms' => new OTPFactory($type, $user)
        };

        $this->user = $user;

        $this->reflection = new \ReflectionClass($this->channel);
    }

    public function setup(): bool
    {

        return true;
    }

    public function verify()
    {

    }


}
