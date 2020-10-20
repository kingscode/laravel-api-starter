<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Registration;

use Illuminate\Foundation\Http\FormRequest;

final class VerifyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email:rfc,dns',],
            'token'    => ['required', 'string'],
            'password' => ['required', 'string', 'min:10', 'confirmed'],
        ];
    }
}
