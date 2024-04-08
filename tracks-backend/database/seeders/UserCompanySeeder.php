<?php

namespace Database\Seeders;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Company;
use App\Models\Person;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $code = "demo";
        $companyName = "Empresa ONERpm";
        $userEmailDemo = 'admin@onerpm.com.br';
        $userEmail = 'rodolpho.paula@outlook.com.br';

        $company = Company::firstOrCreate(
            ['code' => $code],
            [
                'uuid' => Uuid::uuid4(),
                'code' => $code,
                'name' => $companyName,
                'status' => Company::STATUS_ACTIVE
            ]
        );

        $userDemo = User::firstOrCreate(
            ['email' => $userEmailDemo],
            [
                'name' => 'Demo',
                'email' => $userEmailDemo,
                'password' => bcrypt('password'),
                'company_id' => $company->id,
                'status' => User::STATUS_ACTIVE,
                'is_admin' => true
            ]
        );

        $user = User::firstOrCreate(
            ['email' => $userEmail],
            [
                'name' => 'Rodolpho de Paula',
                'email' => $userEmail,
                'password' => bcrypt('password'),
                'company_id' => $company->id,
                'status' => User::STATUS_ACTIVE,
                'is_admin' => true
            ]
        );

        Person::firstOrCreate(
            ['email' => $userDemo->email],
            [
                'name' => $userDemo->name,
                'email' => $userDemo->email,
                'company_id' => $userDemo->company->id,
                'user_id' => $userDemo->id,
                'enrollment' => random_int(1000, 9999),
            ]
        );

        Person::firstOrCreate(
            ['email' => $user->email],
            [
                'name' => $user->name,
                'email' => $user->email,
                'company_id' => $user->company->id,
                'user_id' => $user->id,
                'enrollment' => random_int(1000, 9999),
            ]
        );
    }
}
