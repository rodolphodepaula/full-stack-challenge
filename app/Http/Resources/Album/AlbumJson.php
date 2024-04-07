<?php
namespace App\Http\Resources\Album;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumJson extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'thumb_path' => $this->thumb_path,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}