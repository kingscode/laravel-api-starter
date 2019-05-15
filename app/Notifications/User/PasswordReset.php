<?php

declare(strict_types=1);

namespace App\Notifications\User;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use function App\Support\front_url;
use function config;
use function http_build_query;

final class PasswordReset extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string $token
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  User $user
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(User $user): MailMessage
    {
        $query = http_build_query([
            'token' => $this->token,
            'email' => $user->getEmailForPasswordReset(),
        ]);

        $url = front_url('password/reset?' . $query);

        return (new MailMessage())
            ->subject(Lang::getFromJson('Reset Password Notification'))
            ->line(Lang::getFromJson('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::getFromJson('Reset Password'), $url)
            ->line(Lang::getFromJson('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.user-invitations.expire')]))
            ->line(Lang::getFromJson('If you did not request a password reset, no further action is required.'));
    }
}