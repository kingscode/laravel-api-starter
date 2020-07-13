<?php

declare(strict_types=1);

namespace Tests\Unit\Mail\Registration;

use App\Mail\Registration\Verify;
use Tests\TestCase;

final class VerifyTest extends TestCase
{
    public function test()
    {
        $mailable = (new Verify('yayeeeeeeet', 'info@kingscode.nl'));
        $mailable->render();

        $this->assertArrayHasKey('front_end_url', $mailable->viewData);
        $this->assertArrayHasKey('verify_url', $mailable->viewData);
        $this->assertStringContainsString('info@kingscode.nl', $mailable->viewData['verify_url']);
        $this->assertStringContainsString('yayeeeeeeet', $mailable->viewData['verify_url']);
    }
}
