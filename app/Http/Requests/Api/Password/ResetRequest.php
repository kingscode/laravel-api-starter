<?php

namespace App\Http\Requests\Api\Password;

use Illuminate\Foundation\Http\FormRequest;

final class ResetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token'    => ['required', 'string'],
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:10', 'max:191', 'confirmed'],
        ];
    }
}
