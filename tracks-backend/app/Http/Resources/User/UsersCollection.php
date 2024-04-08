<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UsersCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function ($model) {
            return new UserJson($model);
        });
    }
}
