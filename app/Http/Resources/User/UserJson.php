<?php

namespace App\Http\Resources\User;

use App\Http\Resources\AllResources;
use App\Http\Resources\Company\CompanyCollection;
use App\Models\Company;
use Illuminate\Http\Resources\Json\JsonResource;

class UserJson extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'is_admin' => $this->isMaster(),
            'company' => $this->company->name,
            'company_uuid' => $this->company->uuid,
            'person' => $this->person ?? null,
            'email' => $this->email,
            'enrollment' => $this->enrollment,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function includeCompanies()
    {
        $companies = Company::all();

        $this->item('companies', new CompanyCollection($companies));
    }
}