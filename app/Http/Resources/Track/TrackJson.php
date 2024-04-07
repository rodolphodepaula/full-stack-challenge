<?php

namespace App\Http\Resources\Track;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackJson extends JsonResource
{
    public function toArray($request)
    {
        return [
            'album_thumb' => "",
            'release_date' => "",
            'track_title' => "",
            'artists' => "",
            'duration' => "",
            'preview_url' => "",
            'spotify_url' => "",
            'available_in_br' => "",
        ];
    }

}
