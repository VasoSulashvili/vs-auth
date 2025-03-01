<?php

namespace VS\Auth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Route;
use VS\Base\Exceptions\APIException;

class ResetPasswordEmail extends ResetPasswordNotification
{
    use Queueable;

    protected $routeName;

    public function __construct($token, string $routeName)
    {
        parent::__construct($token);
        $this->routeName = $routeName;
    }
    /**
     * Create a new notification instance.
     */


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
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Admin Password Reset Request')
            ->line('Click the button below to reset your password.')
            ->action('Reset Password', $this->resetUrl($notifiable))
            ->line('If you did not request a password reset, no further action is required.');
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

    protected function resetUrl($notifiable): string
    {
        if (Route::has($this->routeName)) {
            return route($this->routeName, [
                'token' => $this->token,
                'email' => $notifiable->email,
            ]);
        } else {
            throw new APIException('Route not found.');
        }
    }
}
