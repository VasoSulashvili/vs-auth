<?php

namespace VS\Auth\Enums;

enum TwoFAType: string
{
    case NONE = 'none';
    case SMS = 'sms';
    case EMAIL = 'email';
    case AUTHENTICATOR = 'authenticator';

}
