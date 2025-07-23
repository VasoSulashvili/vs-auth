<?php

namespace VSAuth\Listeners;

use Illuminate\Support\Facades\Mail;
use VSAuth\Events\UserRegistered;
use VSAuth\Mail\WelcomeEmail;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
    }
}
