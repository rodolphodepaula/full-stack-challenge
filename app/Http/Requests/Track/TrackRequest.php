<?php

namespace App\Http\Requests\Track;

use Illuminate\Foundation\Http\FormRequest;

class TrackRequest  extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'album_uuid' => 'sometimes|uuid|exists:albums,uuid',
            'artist_uuid' => 'required|uuid|exists:artists,uuid',
            'isrc' => 'required|string|max:15',
            'title' => 'required|string|max:255',
            'release_date' => 'required|date',
            'duration' => 'required|string',
            'spotify_url' => 'nullable|url',
            'available_in_brazil' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'album_uuid.sometimes' => 'O identificador do álbum é necessário em algumas situações.',
            'album_uuid.uuid' => 'O identificador do álbum deve ser um UUID válido.',
            'album_uuid.exists' => 'O album selecionado não existe.',
            'artist_uuid.required' => 'O campo uuid do artista é obrigatório.',
            'artist_uuid.uuid' => 'O campo uuid do artista deve ser um UUID válido.',
            'artist_uuid.exists' => 'O artista selecionado não existe.',
            'isrc.required' => 'O código ISRC é obrigatório.',
            'isrc.string' => 'O código ISRC deve ser uma string.',
            'isrc.max' => 'O código ISRC não deve ter mais de 15 caracteres.',
            'title.required' => 'O título da faixa é obrigatório.',
            'title.string' => 'O título deve ser uma string.',
            'title.max' => 'O título não deve ter mais de 255 caracteres.',
            'release_date.required' => 'A data de lançamento é obrigatória.',
            'release_date.date' => 'A data de lançamento deve ser uma data válida.',
            'duration.required' => 'A duração da faixa é obrigatória.',
            'duration.string' => 'A duração da faixa deve ser uma string.',
            'spotify_url.url' => 'A URL do Spotify deve ser um URL válido.',
            'available_in_brazil.required' => 'É obrigatório informar se a faixa está disponível no Brasil.',
            'available_in_brazil.boolean' => 'O campo disponível no Brasil deve ser verdadeiro ou falso.',
        ];
    }


}
