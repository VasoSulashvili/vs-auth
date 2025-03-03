<?php

namespace VS\Auth\Enums;

enum OTPAction: string
{
    case ENABLE_2FA = 'enable_2fa';
    case LOGIN = 'login';
    case VALIDATE_EMAIL = 'validate_email';



}
