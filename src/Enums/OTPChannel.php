<?php

namespace VS\Auth\Enums;

enum OTPChannel: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
}
