<?php

namespace App\Http\Requests\Album;

use Illuminate\Foundation\Http\FormRequest;

class AlbumRequest extends FormRequest
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
            'title' => 'required|string|min:3',
            'thumb_path'=> 'sometimes',
            'company_uuid' => 'sometimes|uuid',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'O campo title é obrigatório.',
            'title.string' => 'O campo title deve ser uma string.',
            'title.min' => 'O campo title deve ter pelo menos :min caracteres.',
        ];
    }
}
