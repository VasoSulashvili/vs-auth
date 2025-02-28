<?php

namespace VS\Auth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;

class VerifyApiEmail extends VerifyEmail
{
    use Queueable;

    protected $verificationVerifyRouteName;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $verificationVerifyRouteName)
    {
        $this->verificationVerifyRouteName = $verificationVerifyRouteName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
//    public function via(object $notifiable): array
//    {
//        return ['mail'];
//    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->line('Click the button below to verify your email.')
            ->action('Verify Email', $verificationUrl)
            ->line('If you did not create an account, no further action is required.');
    }

    protected function verificationUrl($notifiable)
    {
        // Generate URL for API verification
        return URL::signedRoute(
            $this->verificationVerifyRouteName,
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
