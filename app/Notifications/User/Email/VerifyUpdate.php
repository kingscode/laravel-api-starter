<?php

declare(strict_types=1);

namespace App\Notifications\User\Email;

use App\Auth\EmailDispensary;
use App\Models\User;
use App\SPA\UrlGenerator;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class VerifyUpdate extends Notification
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
        $url = app(UrlGenerator::class)->to('profile/email/verify') . "#token={$this->token}&email={$user->getEmail()}";

        return (new MailMessage())
            ->subject('Email address update request')
            ->line('You are receiving this email because you\'ve requested to change your email address.')
            ->line('To verify this update click on the button below.')
            ->action('Verify email address', $url)
            ->line('This email will expire in ' . EmailDispensary::TTL / 60 . ' minutes.');
    }
}
