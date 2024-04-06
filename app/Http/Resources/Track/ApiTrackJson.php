<?php

namespace App\Http\Resources\Track;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiTrackJson extends JsonResource
{
    public function toArray($request)
    {
        return [
            'album_thumb' => $this['album']['images'][0]['url'] ?? null,
            'release_date' => $this['album']['release_date'] ?? null,
            'track_title' => $this['name'] ?? null,
            'artists' => collect($this['artists'])->pluck('name'),
            'duration' => gmdate('i:s', $this['duration_ms'] / 1000),
            'preview_url' => $this['preview_url'] ?? null,
            'spotify_url' => $this['external_urls']['spotify'] ?? null,
            'available_in_br' => in_array('BR', $this['available_markets'] ?? []),
        ];
    }

}
