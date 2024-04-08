<?php

namespace Tests\Unit;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompaniesTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_validar_cadastro_company()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);

        $code = 001;
        $companyName = "X";

        $data = [
            'uuid' => Uuid::uuid4(),
            'code' => $code,
            'name' => $companyName,
            'status' => Company::STATUS_ACTIVE
        ];

        $response = $this->api('POST', 'companies', $data)
            ->assertStatus(422)
            ->json('errors');

        $this->assertEquals($response['name'][0], "O campo Nome deve ter pelo menos 3 caracteres.");
        $this->assertEquals($response['code'][0], "O campo Code deve ser uma string.");
        $this->assertEquals($response['code'][1], "O campo Code deve ter pelo menos 3 caracteres.");
    }


    public function test_mostrar_company_cadastrado()
    {
        $code = "demo";
        $companyName = "Empresa ONERpm";

        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);

        $company = Company::create(
            [
                'uuid' => Uuid::uuid4(),
                'code' => $code,
                'name' => $companyName,
                'status' => Company::STATUS_ACTIVE
            ]
        );

        $response = $this->api('GET', 'companies/'.$company->uuid)->assertStatus(200)->json('data');
        $this->assertEquals($response['code'], $code);
    }

    public function test_editar_company_cadastrado()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);

        $companies = Company::factory()->count(2)->make();
        $company = $companies->first();
        $company->uuid = Uuid::uuid4();
        $company->save();

        $name = "ONERpm - Brasil";
        $code = "ONERpm";

        $response = $this->api('PUT', 'companies/'.$company->uuid, [
            'name' => $name,
            'code' => $code,
            'status' => Company::STATUS_ACTIVE
        ]);

        $response = $this->api('GET', 'companies/'.$company->uuid)->assertStatus(200)->json('data');
        $this->assertEquals($response['name'], $name);
        $this->assertEquals($response['code'], $code);
    }

    public function test_deletar_company_cadastrado()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);

        $companies = Company::factory()->count(1)->make();
        $company = $companies->first();
        $company->uuid = Uuid::uuid4();
        $company->save();

        $this->api('DELETE', 'companies/'.$company->uuid)->assertStatus(200);
        $this->api('GET', 'companies/'.$company->uuid)->assertStatus(404);
    }

}
