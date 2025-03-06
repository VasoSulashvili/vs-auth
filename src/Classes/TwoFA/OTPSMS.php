<?php

namespace VS\Auth\Classes\TwoFA;

use Illuminate\Support\Facades\Mail;
use VS\Auth\Mail\SendPin;
use VS\Base\Exceptions\APIException;

class OTPSMS implements OTPInterface
{
    public function send(): bool
    {
//        if (!filter_var($this->user->phone, FILTER_VALIDATE_EMAIL)) {
//            throw new APIException('Invalid phone format.');
//        }

        if (!filter_var($this->pin, FILTER_VALIDATE_INT)) {
            throw new APIException('Invalid pin format.');
        }

        Mail::to($this->user->email)->send(new SendPin($this->pin));
        return true;
    }

}
