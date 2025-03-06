<?php

namespace VS\Auth\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use VS\Auth\Models\OTP;

trait HasPin
{
    public function pins(): MorphOne
    {
        return $this->morphOne(OTP::class, 'otpable');
    }

    public function latestPin(): MorphOne
    {
        return $this->morphOne(OTP::class, 'otpable')->latestOfMany();
    }
}
