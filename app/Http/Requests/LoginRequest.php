<?php

namespace App\Modules\User\Http\Requests;

use App\Shared\ApiFormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'phone' => ['required', 'regex:/^(002)?01[0-2]\d{8}$/', 'min:10', 'exists:users,phone'],
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => '002'.$this->phone
        ]);
    }

    public function messages(): array
    {
        return [
            'password.letters' => 'The password must contain at least one letter.',
            'password.numbers' => 'The password must contain at least one number.',
        ];
    }
}
