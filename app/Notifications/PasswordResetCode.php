<?php

// php artisan make:notification PasswordResetCode
// app/Notifications/PasswordResetCode.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetCode extends Notification implements ShouldQueue
{
    // use Queueable;

    public function __construct(public string $code) {}

    public function via($notifiable): array
    {
        return ['mail']; // add 'nexmo', 'twilio', etc. if you send SMS
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name')) // From address from config
            ->subject('Your Rolla password reset code')
            ->greeting('Hi ' . $notifiable->name) // Can use $notifiable->name or $notifiable->email
            ->line('Use this verification code to reset your password:')
            ->line('**' . $this->code . '**') // Display the reset code
            ->line('This code will expire in 10 minutes.');
    }
}
