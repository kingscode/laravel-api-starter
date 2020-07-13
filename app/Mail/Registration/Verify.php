<?php

declare(strict_types=1);

namespace App\Mail\Registration;

use App\SPA\UrlGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class Verify extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    private string $token;

    private string $email;

    public function __construct(string $token, string $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build(UrlGenerator $urlGenerator)
    {
        return $this->markdown('mail.registration.verify')
            ->subject('Registration verification')
            ->with([
                'front_end_url' => $urlGenerator->to(''),
                'verify_url'    => $urlGenerator->to('registration/verify') . "#token={$this->token}&email={$this->email}",
            ]);
    }
}
