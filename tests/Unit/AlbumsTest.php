<?php

namespace Tests\Unit;

use App\Models\Album;
use App\Models\Company;
use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlbumsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_validar_cadastro_album()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);
        $albumTitle = "h";

        $company = Company::factory()->create([
            'name' => $this->faker->company,
            'code' => 'ONERpm',
            'status' => Company::STATUS_ACTIVE,
        ]);

        $data = [
            'uuid' => Uuid::uuid4(),
            'title' => $albumTitle,
            'thumb_path' => $this->faker->imageUrl,
            'company_id' => $company->id
        ];

        $response = $this->api('POST', 'albums', $data)
            ->assertStatus(422)
            ->json('errors');

        $this->assertEquals($response['title'][0], "O campo title deve ter pelo menos 3 caracteres.");
    }


    public function test_mostrar_album_cadastrado()
    {
        $albumName = $this->faker->name;

        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);

        $company = Company::factory()->create([
            'name' => $this->faker->company,
            'code' => 'ONERpm',
            'status' => Company::STATUS_ACTIVE,
        ]);

        $title = $this->faker->title;
        $album = album::create(
            [
                'uuid' => Uuid::uuid4(),
                'title' => $title,
                'thumb_path' => $this->faker->imageUrl,
                'company_id' => $company->id
            ]
        );

        $response = $this->api('GET', 'albums/'.$album->uuid)->assertStatus(200)->json('data');
        $this->assertEquals($response['title'], $title);
    }

    public function test_editar_album_cadastrado()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);

        $company = Company::factory()->create([
            'name' => $this->faker->company,
            'code' => 'ONERpm',
            'status' => Company::STATUS_ACTIVE,
        ]);

        $albums = Album::factory()->count(2)->make();
        $album = $albums->first();
        $album->uuid = Uuid::uuid4();
        $album->save();

        $title = $this->faker->title;
        $response = $this->api('PUT', 'albums/'.$album->uuid, [
            'title' => $title,
            'thumb_path' => $this->faker->imageUrl,
        ]);

        $response = $this->api('GET', 'albums/'.$album->uuid)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals($response['title'], $title);
    }

    public function test_deletar_album_cadastrado()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);

        $albums = Album::factory()->count(1)->make();
        $album = $albums->first();
        $album->uuid = Uuid::uuid4();
        $album->save();

        $this->api('DELETE', 'albums/'.$album->uuid)->assertStatus(200);
        $this->api('GET', 'albums/'.$album->uuid)->assertStatus(404);
    }

}
