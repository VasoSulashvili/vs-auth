<?php

namespace VS\Auth\Classes\TwoFA;

interface TwoFAInterface
{
    public function send();
    public function verify();

}
