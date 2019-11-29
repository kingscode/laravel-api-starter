<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Invitation;

use Illuminate\Foundation\Http\FormRequest;

final class AcceptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'token'    => ['required', 'string'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:10', 'max:191', 'confirmed'],
        ];
    }
}
