<?php

namespace VS\Auth\Enums;

enum OTPStatus: string
{
    case VALID = 'valid';
    case VERIFIED = 'verified';
    case EXPIRED = 'expired';
    case UNVERIFIED = 'unverified';

}
