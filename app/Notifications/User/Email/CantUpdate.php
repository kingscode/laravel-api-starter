<?php

declare(strict_types=1);

namespace App\Notifications\User\Email;

use App\Auth\EmailDispensary;
use App\Models\User;
use App\SPA\UrlGenerator;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use function config;

final class CantUpdate extends Notification
{
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(User $user): MailMessage
    {
        return (new MailMessage())
            ->subject('Email address update request')
            ->line('You are receiving this email because you\'ve requested to change your email address.')
            ->line('Sadly, you already have an account with this email address in place and we are unable change it.');
    }
}
