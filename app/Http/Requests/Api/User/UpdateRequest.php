<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $user = $this->route()->parameter('user');

        return [
            'name'  => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($user)],
        ];
    }
}
