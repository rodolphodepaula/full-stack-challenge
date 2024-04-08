<?php
namespace App\Http\Resources\Artist;

use App\Models\Artist;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArtistCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function ($model) {
            return new ArtistJson($model);
        });
    }
}
