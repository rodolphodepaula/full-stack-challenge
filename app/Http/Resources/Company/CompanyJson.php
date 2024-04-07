<?php

namespace App\Http\Resources\Company;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyJson extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'code' => $this->code,
            'status' => $this->status,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i'),
        ];
    }
}