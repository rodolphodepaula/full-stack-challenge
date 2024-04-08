<?php

namespace App\Services\Track;

use DB;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Track;
use App\Services\AbstractService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

class TrackService extends AbstractService
{
    public function getBySearch(Builder $track, array $search = []): Builder
    {
        if (!empty($search['search'])) {
            $track = $track->where('albums.title', 'LIKE', '%' . $search['search'] . '%');
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

    public function save(array $params): Track
    {
        return DB::transaction(function () use ($params) {
            $albumId = Album::whereUuid($params['album_uuid'])->value('id');

            if (!$albumId) {
                throw new \Exception("Álbum ou artista não encontrado.");
            }

            $track = new Track([
                'album_id' => $albumId,
                'isrc' => $params['isrc'],
                'release_date' => $params['release_date'],
                'duration' => $params['duration'],
                'spotify_url' => $params['spotify_url'],
                'available_in_brazil' => $params['available_in_brazil'],
            ]);

            $track->save();

            $artistUuids = is_array($params['artist_uuids']) ? $params['artist_uuids'] : explode(',', $params['artist_uuids']);
            $artistIds = Artist::whereIn('uuid', $artistUuids)->pluck('id')->toArray();
            $track->artists()->attach($artistIds);

            return $track;
        });
    }

    public function update(Track $track, array $params): Track
    {
        return DB::transaction(function () use ($track, $params) {
            $albumId = Album::whereUuid($params['album_uuid'])->value('id');

            if (!$albumId) {
                throw new Exception("Álbum não encontrado.");
            }

            if (!$track) {
                throw new Exception("Faixa não encontrada.");
            }

            $track->update([
                'album_id' => $albumId,
                'isrc' => $params['isrc'],
                'release_date' => $params['release_date'],
                'duration' => $params['duration'],
                'spotify_url' => $params['spotify_url'],
                'available_in_brazil' => $params['available_in_brazil'],
            ]);

            return $track;
        });
    }

    public function delete(Track $track): Track
    {
        return DB::transaction(function () use ($track) {
            $track->artists()->detach();
            $track->delete();

            return $track;
        });
    }
}
