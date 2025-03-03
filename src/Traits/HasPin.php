<?php

namespace VS\Auth\Traits;

use VS\Auth\Models\OTP;

trait HasPin
{
    public function pin()
    {
        return $this->morphOne(OTP::class, 'otpable');
    }
}
