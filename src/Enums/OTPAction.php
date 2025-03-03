<?php

namespace VS\Auth\Enums;

enum OTPAction: string
{
    case LOGIN = 'login';
    case VALIDATE_EMAIL = 'validate_email';

}
