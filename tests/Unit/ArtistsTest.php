<?php

namespace Tests\Unit;

use App\Models\Artist;
use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtistsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_validar_cadastro_artista()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);
        $artistName = "a";

        $data = [
            'uuid' => Uuid::uuid4(),
            'name' => $artistName,
        ];

        $response = $this->api('POST', 'artists', $data)
            ->assertStatus(422)
            ->json('errors');

        $this->assertEquals($response['name'][0], "O campo Nome deve ter pelo menos 3 caracteres.");
    }


    public function test_mostrar_artista_cadastrado()
    {
        $artistName = $this->faker->name;

        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);

        $artist = Artist::create(
            [
                'uuid' => Uuid::uuid4(),
                'name' => $artistName,
            ]
        );

        $response = $this->api('GET', 'artists/'.$artist->uuid)->assertStatus(200)->json('data');
        $this->assertEquals($response['name'], $artistName);
    }

    public function test_editar_artista_cadastrado()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);

        $artists = Artist::factory()->count(2)->make();
        $artist = $artists->first();
        $artist->uuid = Uuid::uuid4();
        $artist->save();

        $name = $this->faker->name;
        $response = $this->api('PUT', 'artists/'.$artist->uuid, [
            'name' => $name,
        ]);

        $response = $this->api('GET', 'artists/'.$artist->uuid)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals($response['name'], $name);
    }

    public function test_deletar_artist_cadastrado()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);

        $this->setAuthUser($user);

        $artists = Artist::factory()->count(1)->make();
        $artist = $artists->first();
        $artist->uuid = Uuid::uuid4();
        $artist->save();

        $this->api('DELETE', 'artists/'.$artist->uuid)->assertStatus(200);
        $this->api('GET', 'artists/'.$artist->uuid)->assertStatus(404);
    }

}
