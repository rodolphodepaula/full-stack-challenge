<?php

namespace App\Http\Resources\Track;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackJson extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'album_uuid' => $this->album->uuid,
            'album_title' => $this->album->title,
            'album_thumb' => $this->album->thumb_path,
            'release_date' => $this->release_date,
            'artists' => $this->artists->map(function ($artist) {
                return [
                    'uuid' => $artist->uuid,
                    'name' => $artist->name
                ];
            }),
            'duration' => $this->duration,
            'spotify_url' => $this->spotify_url,
            'preview_url' => $this->preview_url,
            'available_in_br' => $this->available_in_brazil,
            'isrc' => $this->isrc,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i'),
        ];
    }
}
