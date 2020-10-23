<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Auth\LoginDispensary;
use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

final class Login
{
    private ResponseFactory $responseFactory;

    private LoginDispensary $dispensary;

    private Hasher $hasher;

    private Translator $translator;

    public function __construct(
        ResponseFactory $responseFactory,
        LoginDispensary $dispensary,
        Hasher $hasher,
        Translator $translator
    ) {
        $this->responseFactory = $responseFactory;
        $this->dispensary = $dispensary;
        $this->hasher = $hasher;
        $this->translator = $translator;
    }

    /**
     * @param  \App\Http\Requests\Api\Auth\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->input('email'))->first();

        if (! $user instanceof User) {
            $this->fail();
        }

        if (! $this->hasher->check($request->input('password'), $user->getAuthPassword())) {
            $this->fail();
        }

        return $this->responseFactory->json([
            'data' => [
                'token' => $this->dispensary->dispense($user),
            ],
        ]);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    private function fail(): void
    {
        throw ValidationException::withMessages([
            'email' => $this->translator->get('auth.failed'),
        ]);
    }
}
