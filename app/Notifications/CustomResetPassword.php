<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Services\SendingEmail;

class CustomResetPassword extends Notification
{
    use Queueable;

    protected $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // This triggers toMail(), but we override it to use custom sender
        return [];
    }

    /**
     * Send the custom email using your service.
     */
    public function sendCustomEmail($notifiable)
    {
        $resetUrl = config('app.frontend_url') . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email);

        $subject = 'Reset Your Password';

        $body = view('reset-password', [
            'resetUrl' => $resetUrl,
        ])->render();

        return (new SendingEmail(
            email: $notifiable->email,
            body: $body,
            subject: $subject,
            key: 'reset-password'
        ))->send();
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }

    protected function resetUrl($notifiable)
    {
        // Optional: Use your frontend's password reset route instead
        return config('app.frontend_url') . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email);
    }
}
