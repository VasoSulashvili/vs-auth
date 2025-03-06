<?php

namespace VS\Auth\Classes\TwoFA;

class TOTPFactory implements TwoFAInterface
{
    public function setup(): bool
    {

        return true;
    }

    public function verify()
    {

    }
}

