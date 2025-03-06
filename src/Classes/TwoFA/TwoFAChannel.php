<?php

namespace VS\Auth\Classes\TwoFA;

enum TwoFAChannel: string
{
    case EMAIL = 'email';

    case SMS = 'sms';

    case TOTP = 'totp';


    public function getCreator(): TwoFAFactory
    {
        return match ($this) {
            self::TOTP => new TOTPTwoFAFactory(),
            self::EMAIL, self::SMS =>  new OTPCreator($this->value),
        };
    }

}
