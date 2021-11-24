<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Api\Auth;

use App\Auth\Dispensary\Repository;
use App\Auth\LoginDispensary;
use App\SPA\UrlGenerator;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Config;
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
    public function test()
    {
        $user = UserFactory::new()->createOne([
            'password' => bcrypt('kingscodedotnl'),
        ]);

        $token = $this->dispensary->dispense($user);

        $response = $this
            ->withHeader('Referer', 'https://www.kingscode.nl/')
            ->json('post', 'auth/dispense', [
                'email' => $user->email,
                'token' => $token,
            ]);

        $response->isRedirect();

        $redirectUri = $response->headers->get('Location');

        $this->assertStringContainsString($this->urlGenerator->to('auth/callback'), $redirectUri);

        $this->assertStringContainsString('token=', parse_url($redirectUri, PHP_URL_FRAGMENT));
    }

    public function testWithReferer()
    {
        Config::set('spa.force_url', false);

        $user = UserFactory::new()->createOne([
            'password' => bcrypt('kingscodedotnl'),
        ]);

        $token = $this->dispensary->dispense($user);

        $response = $this
            ->withHeader('Referer', 'https://www.kingscode.nl/team')
            ->json('post', 'auth/dispense', [
                'email' => $user->email,
                'token' => $token,
            ]);

        $response->isRedirect();

        $redirectUri = $response->headers->get('Location');

        $this->assertStringContainsString('https://www.kingscode.nl/auth/callback', $redirectUri);

        $this->assertStringContainsString('token=', parse_url($redirectUri, PHP_URL_FRAGMENT));
    }

    public function testWithRefererIncludingPort()
    {
        Config::set('spa.force_url', false);

        $user = UserFactory::new()->createOne([
            'password' => bcrypt('kingscodedotnl'),
        ]);

        $token = $this->dispensary->dispense($user);

        $response = $this
            ->withHeader('Referer', 'https://www.kingscode.nl:8080/team')
            ->json('post', 'auth/dispense', [
                'email' => $user->email,
                'token' => $token,
            ]);

        $response->isRedirect();

        $redirectUri = $response->headers->get('Location');

        $this->assertStringContainsString('https://www.kingscode.nl:8080/auth/callback', $redirectUri);

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

        $this->dispensary->dispense($user);

        $response = $this->json('post', 'auth/dispense', [
            'email' => $user->email,
            'token' => 'shizzlepizza',
        ]);

        $redirectUri = $response->headers->get('Location');

        $this->assertStringContainsString($this->urlGenerator->to('auth/callback'), $redirectUri);
    }

    public function testRedirectsBackWhenEmailDoesntExists()
    {
        UserFactory::new()->createOne([
            'email'    => 'yoink@dadoink.nl',
            'password' => bcrypt('kingscodedotnl'),
        ]);

        $response = $this->json('post', 'auth/dispense', [
            'email' => 'testing@kingscode.nl',
            'token' => 'shizzlepizza',
        ]);

        $redirectUri = $response->headers->get('Location');

        $this->assertStringContainsString($this->urlGenerator->to('auth/callback'), $redirectUri);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('spa.force_url', true);
        Config::set('spa.url', 'http://localhost:1337');
        $this->dispensary = $this->app->make(LoginDispensary::class);
        $this->urlGenerator = $this->app->make(UrlGenerator::class);
    }

    protected function tearDown(): void
    {
        unset($this->dispensary, $this->urlGenerator);
        parent::tearDown();
    }
}
