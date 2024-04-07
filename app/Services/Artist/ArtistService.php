<?php
namespace App\Services\Artist;

use DB;
use App\Models\Artist;
use App\Services\AbstractService;
use Illuminate\Database\Eloquent\Builder;

class ArtistService extends AbstractService
{
    public function getBySearch(Builder $artist, array $search = []): Builder
    {
        if (! empty($search['search']) ?? '') {
            $artist->where('artists.name', 'LIKE', '%'.$search['search'].'%');
        }

        if (! empty($search['name']) ?? '') {
            $artist->where('artists.name', 'LIKE', '%'.$search['name'].'%');
        }

        return $artist;
    }


    public function save(array $params): Artist
    {
        return DB::transaction(function () use ($params) {
            $artist = new Artist([
                'name' => $params['name'],
            ]);

            $artist->save();

            return $artist;
        });
    }

    public function update(Artist $artist, array $params): Artist
    {
        return DB::transaction(function () use ($artist, $params) {
            if (isset($params['name'])) {
                $artist->name = $params['name'];
            }

            $artist->save();

            return $artist;
        });
    }

    public function delete(Artist $artist): Artist
    {
        return DB::transaction(function () use ($artist) {
            $artist->delete();

            return $artist;
        });
    }
}