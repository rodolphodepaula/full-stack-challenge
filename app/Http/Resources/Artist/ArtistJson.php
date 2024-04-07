<?php
namespace App\Http\Resources\Artist;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistJson extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}