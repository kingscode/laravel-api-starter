<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Auth\Dispensary\Exceptions\TokenExpiredException;
use App\Auth\LoginDispensary;
use App\Contracts\Http\Responses\ResponseFactory;
use App\Models\User;
use App\Models\UserToken;
use App\SPA\UrlGenerator;
use Illuminate\Config\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class Dispense
{
    private ResponseFactory $responseFactory;

    private UrlGenerator $urlGenerator;

    private LoginDispensary $dispensary;

    private Repository $config;

    public function __construct(
        ResponseFactory $responseFactory,
        UrlGenerator $urlGenerator,
        LoginDispensary $dispensary,
        Repository $config
    ) {
        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->dispensary = $dispensary;
        $this->config = $config;
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $url = $this->createUrl($request);

        if (! $request->filled(['email', 'token'])) {
            return $this->responseFactory->redirectTo($url);
        }

        $user = User::query()->where('email', $request->input('email'))->first();

        if (! $user instanceof User) {
            return $this->responseFactory->redirectTo($url);
        }

        try {
            $verified = $this->dispensary->verify($user, $request->input('token'));

            if (! $verified) {
                return $this->responseFactory->redirectTo($url);
            }

            do {
                $token = Str::random(128);
            } while (UserToken::query()->where('token', $token)->exists());

            $user->tokens()->create(['token' => $token]);

            return $this->responseFactory->redirectTo($url . '#token=' . $token);
        } catch (TokenExpiredException $exception) {
            return $this->responseFactory->redirectTo($url);
        }
    }

    private function createUrl(Request $request): string
    {
        if (false === $this->config->get('spa.force_url') &&
            null !== $request->headers->get('referer')) {
            $urlInfo = parse_url($request->headers->get('referer'));
            if (is_array($urlInfo) && isset($urlInfo['scheme']) && isset($urlInfo['host'])) {
                if (isset($urlInfo['port'])) {
                    $urlGenerator = new UrlGenerator("{$urlInfo['scheme']}://{$urlInfo['host']}:{$urlInfo['port']}");
                } else {
                    $urlGenerator = new UrlGenerator("{$urlInfo['scheme']}://{$urlInfo['host']}");
                }

                return $urlGenerator->to('auth/callback', [
                    'redirect_uri' => $request->input('redirect_uri'),
                ]);
            }
        }

        return $this->urlGenerator->to('auth/callback', [
            'redirect_uri' => $request->input('redirect_uri'),
        ]);
    }
}
