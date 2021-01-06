<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Auth\Dispensary\Exceptions\TokenExpiredException;
use App\Auth\LoginDispensary;
use App\Contracts\Http\Responses\ResponseFactory;
use App\Models\User;
use App\Models\UserToken;
use App\SPA\UrlGenerator;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class Dispense
{
    private ResponseFactory $responseFactory;

    private UrlGenerator $urlGenerator;

    private LoginDispensary $dispensary;

    private Translator $translator;

    public function __construct(
        ResponseFactory $responseFactory,
        UrlGenerator $urlGenerator,
        LoginDispensary $dispensary,
        Translator $translator
    ) {
        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->dispensary = $dispensary;
        $this->translator = $translator;
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $url = $this->urlGenerator->to('auth/callback', [
            'redirect_uri' => $request->input('redirect_uri'),
        ]);

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
}
