<?php

namespace App\Services\Person;

use DB;
use App\Models\Company;
use App\Models\Person;
use App\Services\AbstractService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;


class PersonService extends AbstractService
{
    public function getBySearch(Builder $user, array $search = []): Builder
    {
        return $user;
    }

    public function getBySearchApi(string $search): ?array
    {
        return null;
    }

    public function save(array $params): Person
    {
        return DB::transaction(function () use ($params){
            $person = Person::firstOrCreate(
                ['email' =>$params['email']],
                [
                    'name' => $params['name'],
                    'email' => $params['email'],
                    'company_id' => $params['company_id'],
                    'user_id' => $params['user_id'],
                    'enrollment' =>  $params['enrollment'],
                ]
            );

            return $person;
        });
    }

    public function update(array $params): Person
    {
        return DB::transaction(function () use ($params){
            $person = Person::where('user_id', $params['user_id'] ?? '')->firstOrFail();
            $person->company_id = $params['company_id'];
            $person->company_id = $params['company_id'];
            $person->name = $params['name'];
            $person->email = $params['email'];
            $person->enrollment = $params['enrollment'];
            $person->save();

            return $person;
        });
    }

}