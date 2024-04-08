<?php

namespace App\Http\Resources\Track;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackJson extends JsonResource
{
    public function toArray($request)
    {
        return [
            'album_thumb' => $this->album->thumb_path,
            'release_date' => $this->release_date,
            'title' => $this->title,
            'artists' => $this->artists->pluck('name'),
            'duration' => $this->duration,
            'spotify_url' => $this->spotify_url,
            'available_in_br' => $this->available_in_brazil,
            'isrc' => $this->isrc,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i'),
        ];
    }
}
