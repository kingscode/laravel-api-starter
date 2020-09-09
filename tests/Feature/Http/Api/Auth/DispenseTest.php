<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Auth;

use App\Auth\Dispensary\Repository;
use App\Auth\LoginDispensary;
use App\SPA\UrlGenerator;
use Database\Factories\UserFactory;
use Tests\TestCase;
use function bcrypt;
use function parse_url;
use const PHP_URL_FRAGMENT;

/**
 * @property LoginDispensary dispensary
 * @property UrlGenerator    urlGenerator
 */
final class DispenseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->dispensary = $this->app->make(LoginDispensary::class);
        $this->urlGenerator = $this->app->make(UrlGenerator::class);
    }

    public function test()
    {
        $user = UserFactory::new()->createOne([
            'password' => bcrypt('kingscodedotnl'),
        ]);

        $token = $this->dispensary->dispense($user);

        $response = $this->json('post', 'auth/dispense', [
            'email' => $user->email,
            'token' => $token,
        ]);

        $response->isRedirect();

        $redirectUri = $response->headers->get('Location');

        $this->assertStringContainsString($this->urlGenerator->to('auth/callback'), $redirectUri);

        $this->assertStringContainsString('token=', parse_url($redirectUri, PHP_URL_FRAGMENT));
    }

    public function testWithRedirectUri()
    {
        $user = UserFactory::new()->createOne([
            'password' => bcrypt('kingscodedotnl'),
        ]);

        $token = $this->dispensary->dispense($user);

        $response = $this->json('post', 'auth/dispense', [
            'email'        => $user->email,
            'token'        => $token,
            'redirect_uri' => 'to-here',
        ]);

        $response->isRedirect();

        $redirectUri = $response->headers->get('Location');

        $this->assertStringContainsString($this->urlGenerator->to('auth/callback', ['redirect_uri' => 'to-here']), $redirectUri);

        $this->assertStringContainsString('token=', parse_url($redirectUri, PHP_URL_FRAGMENT));
    }

    public function testRedirectsBackWhenNoDataIsSentInRequest()
    {
        $response = $this->json('post', 'auth/dispense');

        $redirectUri = $response->headers->get('Location');

        $this->assertStringContainsString($this->urlGenerator->to('auth/callback'), $redirectUri);
    }

    public function testRedirectsBackWhenTokenExpired()
    {
        $user = UserFactory::new()->createOne([
            'password' => bcrypt('kingscodedotnl'),
        ]);

        $token = $this->dispensary->dispense($user);

        /** @var \App\Auth\Dispensary\Repository $repository */
        $repository = $this->app->make(Repository::class);

        $repository->clear();

        $response = $this->json('post', 'auth/dispense', [
            'email' => $user->email,
            'token' => $token,
        ]);

        $redirectUri = $response->headers->get('Location');

        $this->assertStringContainsString($this->urlGenerator->to('auth/callback'), $redirectUri);
    }

    public function testRedirectsBackWhenTokenWrong()
    {
        $user = UserFactory::new()->createOne([
            'password' => bcrypt('kingscodedotnl'),
        ]);

        $token = $this->dispensary->dispense($user);

        $response = $this->json('post', 'auth/dispense', [
            'email' => $user->email,
            'token' => 'shizzlepizza',
        ]);

        $redirectUri = $response->headers->get('Location');

        $this->assertStringContainsString($this->urlGenerator->to('auth/callback'), $redirectUri);
    }

    public function testRedirectsBackWhenEmailDoesntExists()
    {
        $user = UserFactory::new()->createOne([
            'email'    => 'yoink@dadoink.nl',
            'password' => bcrypt('kingscodedotnl'),
        ]);

        $response = $this->json('post', 'auth/dispense', [
            'email' => 'info@kingscode.nl',
            'token' => 'shizzlepizza',
        ]);

        $redirectUri = $response->headers->get('Location');

        $this->assertStringContainsString($this->urlGenerator->to('auth/callback'), $redirectUri);
    }
}
