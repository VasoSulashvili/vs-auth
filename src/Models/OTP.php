<?php

namespace VS\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $fillable = [
        'otpable_id',
        'otpable_type',
        'pin',
        'status',
        'expires_at'
    ];

    protected $dates = [
        'expires_at',
    ];

    public function otpable()
    {
        return $this->morphTo();
    }
}
