<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Profile\Password;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password'         => [
                'required',
                'string',
                'min:10',
                'confirmed',
            ],
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    /** @var \App\Models\User $user */
                    $user = $this->user();

                    /** @var Hasher $hasher */
                    $hasher = $this->container->make(Hasher::class);

                    if (! $hasher->check($value, $user->getAuthPassword())) {
                        $fail('Current password is invalid.');
                    }
                },
            ],
        ];
    }
}
