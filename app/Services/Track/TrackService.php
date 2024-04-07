<?php

namespace App\Services\Track;

use DB;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Track;
use App\Services\AbstractService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

class TrackService extends AbstractService
{
    public function getBySearch(Builder $track, array $search = []): Builder
    {
        if (!empty($search['search']) ?? '') {
            $track->whereTitle($search['search']);
        }

        if (!empty($search['title']) ?? '') {
            $track->where('tracks.title', 'LIKE', '%' . $search['title'] . '%');
        }

        if (!empty($search['isrc']) ?? '') {
            $track->where('tracks.isrc', 'LIKE', '%' . $search['isrc'] . '%');
        }

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

    public function saveTrack(array $params)
    {
        return DB::transaction(function () use ($params) {
            $albumId = Album::whereUuid($params['album_uuid'])->value('id');
            $artistId = Artist::whereUuid($params['artist_uuid'])->value('id');

            if (!$albumId || !$artistId) {
                throw new \Exception("Álbum ou artista não encontrado.");
            }

            $track = new Track([
                'album_id' => $albumId,
                'artist_id' => $artistId,
                'isrc' => $params['isrc'],
                'title' => $params['title'],
                'release_date' => $params['release_date'],
                'duration' => $params['duration'],
                'spotify_url' => $params['spotify_url'],
                'available_in_brazil' => $params['available_in_brazil'],
            ]);

            $track->save();

            return $track;
        });
    }
}
