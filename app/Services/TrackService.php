<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TrackService
{
    public function getBySearch(string $param): ?array
    {
        if (empty($param)) {
            return null;
        }

        $endpoint = "search?q=isrc:{$param}&type=track&market=BR";
        $response = Http::spotifyapi()->get($endpoint)->throw();

        if (!$response->successful()) {
            return null;
        }

        return $response->json()['tracks']['items'] ?? [];
    }
}
