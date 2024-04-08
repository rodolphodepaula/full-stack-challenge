<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|min:3|max:50',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->uuid, 'uuid'),
            ],
            'status' => 'sometimes|boolean',
            'enrollment' => 'sometimes',
            'company_uuid' => [
                'sometimes',
                'uuid',
                Rule::exists('companies', 'uuid'),
            ],
            'password' => 'nullable|min:6|max:20|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'O campo Nome deve ser uma string.',
            'name.min' => 'O campo Nome deve ter pelo menos :min caracteres.',
            'name.max' => 'O campo Nome não pode ter mais de :max caracteres.',
            'email.string' => 'O campo E-mail deve ser uma string.',
            'email.lowercase' => 'O campo E-mail deve estar em letras minúsculas.',
            'email.email' => 'O campo E-mail deve ser um endereço de e-mail válido.',
            'email.max' => 'O campo E-mail não pode ter mais de :max caracteres.',
            'email.unique' => 'O E-mail fornecido já está em uso.',
            'status.boolean' => 'O campo Status deve ser verdadeiro (1) ou falso (0).',
            'company_uuid.uuid' => 'O campo Empresa deve ser um UUID válido.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'password.max' => 'A senha não pode ter mais de :max caracteres.',
        ];
    }
}
