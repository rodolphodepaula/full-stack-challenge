<?php

namespace App\Services\Company;

use DB;
use App\Models\Company;
use App\Services\AbstractService;
use Illuminate\Database\Eloquent\Builder;

class CompanyService extends AbstractService
{
    public function getBySearch(Builder $company, array $search = []): Builder
    {
        if (!empty($search['name']) ?? '') {
            $company->whereName($search['name']);
        }

        return $company;
    }

    public function save(array $params): Company
    {
        return DB::transaction(function () use ($params) {
            $company = new Company([
                'name' => $params['name'],
                'code' => $params['code'],
                'status' => $params['status'] ?? false
            ]);

            $company->save();

            return $company;
        });
    }

    public function update(Company $company, array $params): Company
    {
        return DB::transaction(function () use ($company, $params) {
            if (isset($params['name'])) {
                $company->name = $params['name'];
            }

            if (isset($params['code'])) {
                $company->code = $params['code'];
            }

            if (isset($params['status'])) {
                $company->status = $params['status'] ?? false;
            }

            $company->save();

            return $company;
        });
    }

    public function delete(Company $company): Company
    {
        return DB::transaction(function () use ($company) {
            $company->delete();

            return $company;
        });
    }


}
