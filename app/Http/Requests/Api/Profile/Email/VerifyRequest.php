<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Profile\Email;

use Illuminate\Foundation\Http\FormRequest;

final class VerifyRequest extends FormRequest
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
            'email' => ['required', 'string', 'email'],
            'token' => ['required', 'string'],
        ];
    }
}
