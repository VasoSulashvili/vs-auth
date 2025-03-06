<?php

namespace VS\Auth\Classes\TwoFA;

enum OTPChannel: string
{
    case EMAIL = 'email';
    case SMS = 'sms';

    public function getChannel(): OTPInterface
    {
        return match ($this) {
            self::EMAIL => new OTPEmail(),
            self::SMS => new OTPSMS(),
        };
    }
}
