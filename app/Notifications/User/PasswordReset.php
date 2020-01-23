<?php

declare(strict_types=1);

namespace App\Notifications\User;

use App\Models\User;
use App\SPA\UrlGenerator;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use function app;
use function config;

final class PasswordReset extends Notification
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(User $user): MailMessage
    {
        $url = app(UrlGenerator::class)->to('password/reset/' . $this->token, [
            'email' => $user->getEmailForPasswordReset(),
        ]);

        return (new MailMessage())
            ->subject(Lang::get('Reset Password Notification'))
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $url)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.user-invitations.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }
}
