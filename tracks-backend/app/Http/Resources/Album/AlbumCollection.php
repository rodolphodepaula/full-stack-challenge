<?php
namespace App\Http\Resources\Album;


use App\Models\Artist;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AlbumCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function ($model) {
            return new AlbumJson($model);
        });
    }
}
