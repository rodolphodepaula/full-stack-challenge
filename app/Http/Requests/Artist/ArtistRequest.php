<?php

namespace App\Http\Requests\Artist;

use Illuminate\Foundation\Http\FormRequest;

class ArtistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo Nome é obrigatório.',
            'name.string' => 'O campo Nome deve ser uma string.',
            'name.min' => 'O campo Nome deve ter pelo menos :min caracteres.',
        ];
    }
}
