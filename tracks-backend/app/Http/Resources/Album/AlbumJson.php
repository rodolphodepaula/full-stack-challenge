<?php
namespace App\Http\Resources\Album;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumJson extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'company' => $this->company->name,
            'thumb_path' => $this->thumb_path,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i'),
        ];
    }
}