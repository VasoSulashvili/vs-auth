<?php

namespace VS\Auth\Enums;

enum OTPStatus: string
{
    case VALID = 'valid';
    case USED = 'used';
    case EXPIRED = 'expired';

}
