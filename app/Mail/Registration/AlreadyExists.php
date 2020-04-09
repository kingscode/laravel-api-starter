<?php

declare(strict_types=1);

namespace App\Mail\Registration;

use App\SPA\UrlGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class AlreadyExists extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function build(UrlGenerator $urlGenerator)
    {
        return $this->markdown('mail.registration.already_exists')
            ->subject('Registration verification')
            ->with([
                'front_end_url'          => $urlGenerator->to(''),
                'password_forgotten_url' => $urlGenerator->to('password/forgotten'),
            ]);
    }
}
