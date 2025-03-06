<?php

namespace VS\Auth\Classes\TwoFA;

enum OTPStatus: string
{
    case VALID = 'valid';
    case VERIFIED = 'verified';
    case DISABLED = 'disabled';

}
