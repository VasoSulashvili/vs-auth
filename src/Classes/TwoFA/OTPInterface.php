<?php

namespace VS\Auth\Classes\TwoFA;

interface OTPInterface
{
    public function send(): bool;

}
