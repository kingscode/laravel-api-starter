<?php

declare(strict_types=1);

namespace App\Notifications\User;

use App\Models\User;
use App\SPA\UrlGenerator;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use function config;

final class Invitation extends Notification
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
        $url = app(UrlGenerator::class)->to('invitation/accept/' . $this->token, [
            'email' => $user->getEmailForPasswordReset(),
        ]);

        return (new MailMessage())
            ->subject(Lang::get('Account Invitation Notification'))
            ->line(Lang::get('You are receiving this email because an account was created for you.'))
            ->action(Lang::get('Activate account'), $url)
            ->line(Lang::get('This invitation email will expire in :count hours.', ['count' => config('auth.passwords.user-invitations.expire') / 60]));
    }
}
