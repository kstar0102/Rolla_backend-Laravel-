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

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Rolla password reset code')
            ->line('Use this code to reset your password:')
            ->line("**{$this->code}**")
            ->line('This code expires in 10 minutes. If you did not request it, you can ignore this email.');
    }
}
