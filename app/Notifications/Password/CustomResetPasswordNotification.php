<?php

namespace App\Notifications\Password;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends ResetPassword implements shouldQueue
{
    use Queueable;

     /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
        $url_with_path = config('spa.password_reset_url');

        $parsedUrl = parse_url($url_with_path);
        // Log::info(var_dump($parsedUrl));
        $parsedUrl['path'] =  Str::of($parsedUrl['path'])->endsWith('/')? Str::substr($parsedUrl['path'], 0, -1) :  $parsedUrl['path'];
        $parsedUrl['path'] =  Str::of($parsedUrl['path'])->startsWith('/')? Str::substr($parsedUrl['path'], 1) :  $parsedUrl['path'];
        $parsedUrl['host'] =  Str::of($parsedUrl['host'])->endsWith('/')? Str::substr($parsedUrl['host'], 0, -1) :  $parsedUrl['host'];

        $email = urlencode($notifiable->getEmailForPasswordReset());
        $url = "{$parsedUrl['scheme']}://{$parsedUrl['host']}/{$parsedUrl['path']}?token={$this->token}&email={$email}";

        return $this->buildMailMessage($url);
    }
}
