<?php
namespace App\Http\Resources\Artist;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistJson extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i'),
        ];
    }
}