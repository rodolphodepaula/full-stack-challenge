<?php

namespace App\Services\Album;

use DB;
use App\Models\Album;
use App\Services\AbstractService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AlbumService extends AbstractService
{
    public function getBySearch(Builder $album, array $search = []): Builder
    {
        if (! empty($search['search']) ?? '') {
            $album->where('albums.title', 'LIKE', '%'.$search['search'].'%');
        }

        if (! empty($search['title']) ?? '') {
            $album->where('albums.title', 'LIKE', '%'.$search['title'].'%');
        }

        return $album;
    }

    public function save(array $params): Album
    {
        return DB::transaction(function () use ($params) {
            $album = new Album([
                'title' => $params['title'],
                'thumb_path' => $params['thumb_path'],
                'company_id' => Auth::user()->company_id,
            ]);

            $album->save();

            return $album;
        });
    }

    public function update(Album $album, array $params): Album
    {
        return DB::transaction(function () use ($album, $params) {
            if (isset($params['title'])) {
                $album->title = $params['title'];
                $album->thumb_path = $params['thumb_path'];
                $album->thumb_path = $params['thumb_path'];
            }

            $album->save();

            return $album;
        });
    }

    public function delete(Album $album): Album
    {
        return DB::transaction(function () use ($album) {
            $album->delete();

            return $album;
        });
    }
}
