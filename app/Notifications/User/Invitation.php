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

final class Invitation extends Notification
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
            'email' => $user->getEmailForPasswordReset(),
        ]);

        $url = front_url('invitation/accept/' . $this->token . $query);

        return (new MailMessage())
            ->subject(Lang::getFromJson('Account Invitation Notification'))
            ->line(Lang::getFromJson('You are receiving this email because an account was created for you.'))
            ->action(Lang::getFromJson('Activate account'), $url)
            ->line(Lang::getFromJson('This invitation email will expire in :count hours.', ['count' => config('auth.passwords.user-invitations.expire') / 60]));
    }
}
