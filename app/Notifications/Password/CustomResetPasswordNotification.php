<?php

namespace App\Notifications\Password;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
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
        self::createUrlUsing('App\Notifications\Password\CustomResetPasswordNotification::generateUrl');
        $this->token = $token;
        // dd(ResetPassword::$createUrlCallback);
    }

    // /**
    //  * Build the mail representation of the notification.
    //  *
    //  * @param  mixed  $notifiable
    //  * @return \Illuminate\Notifications\Messages\MailMessage
    //  */
    // public function toMail($notifiable)
    // {
    //     if (static::$toMailCallback) {
    //         return call_user_func(static::$toMailCallback, $notifiable, $this->token);
    //     }

    //     if (static::$createUrlCallback) {
    //         $url = call_user_func(static::$createUrlCallback, $notifiable, $this->token);
    //     } else {
    //         $url = url(route('password.reset', [
    //             'token' => $this->token,
    //             'email' => $notifiable->getEmailForPasswordReset(),
    //         ], false));
    //     }

    //     return (new MailMessage)
    //         ->subject(Lang::get('Reset Password Notification'))
    //         ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
    //         ->action(Lang::get('Reset Password'), $url)
    //         ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
    //         ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    // }
  

    public static function generateUrl($notifiable, $token){
        $referer = $_SERVER['HTTP_REFERER'];
        $parsedUrl = parse_url($referer);
        $spaPath = config('spa.password_reset_path');
        $url = "{$parsedUrl['scheme']}://{$parsedUrl['host']}/{$spaPath}/{$token}/{$notifiable->getEmailForPasswordReset()}";
        return $url;
    }


}
