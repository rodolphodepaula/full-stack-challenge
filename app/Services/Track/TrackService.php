<?php
namespace App\Services\Track;

use App\Services\AbstractService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

class TrackService extends AbstractService
{
    public function getBySearch(Builder $track, array $search = []): Builder
    {
        return $track;
    }

    public function getBySearchApi(string $param): ?array
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
