<?php

namespace Tests\Unit;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Company;
use App\Models\Track;
use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class TracksTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_validar_cadastro_faixa()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);
        $this->setAuthUser($user);

        $album = Album::factory()->create([
            'title' => $this->faker->sentence(3),
            'company_id' => Company::factory()->create()->id,
        ]);

        $artist = Artist::factory()->create([
            'name' => $this->faker->name,
        ]);

        $url = $this->faker->url;
        $data = [
            'uuid' => Uuid::uuid4(),
            'duration' => '3:30',
            'album_uuid' => $album->uuid,
            'spotify_url' => $url,
            'artist_uuid' => $artist->uuid,
            'release_date' => '2023-12-16',
            'duration' => '03:21',
            'available_in_brazil' => false,
        ];

        $response = $this->api('POST', 'tracks', $data)
            ->assertStatus(422)
            ->json('errors');

        $this->assertEquals($response['isrc'][0], "O código ISRC é obrigatório.");

        $data['isrc'] = 'USUG11904206';

        $response = $this->api('POST', 'tracks', $data)
            ->assertStatus(201)
            ->json('data');

        $this->assertEquals($response['spotify_url'], $url);
        $this->assertFalse($response['available_in_br']);

    }

    public function test_mostrar_faixa_cadastrada()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => bcrypt('password'),
        ]);
        $this->setAuthUser($user);

        $album = Album::factory()->create([
            'title' => $this->faker->sentence(3),
            'company_id' => Company::factory()->create()->id,
        ]);

        $artist = Artist::factory()->create([
            'name' => $this->faker->name,
        ]);

        $track = Track::factory()->create([
            'album_id' => $album->id,
            'duration' => '03:30'
        ]);


        $response = $this->api('GET', 'tracks/'.$track->uuid)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('03:30', $response['duration']);

    }

    public function test_editar_faixa_cadastrada()
    {
        $user = User::factory()->create([
            'email' => 'test@demo.com',
            'password' => Hash::make('password'),
        ]);

        $this->setAuthUser($user, 'api');

        $album = Album::factory()->create();
        $artist = Artist::factory()->create();

        $track = Track::factory()->create([
            'album_id' => $album->id,
        ]);

        $track->artists()->attach($artist->id);

        $data = [
            'duration' => '4:30',
            'artist_uuid' => $artist->uuid,
            'album_uuid' => $album->uuid,
            'spotify_url' => $this->faker->url,
            'isrc' => 'USUG11904206',
            'release_date' => now()->toDateString(),
            'available_in_brazil' => true,
        ];

        $this->api('PUT', "tracks/{$track->uuid}", $data)
            ->assertStatus(200)
            ->json('data');

        $updatedTrack = Track::find($track->id);
        $this->assertEquals('4:30', $updatedTrack->duration);
    }

    public function test_deletar_faixa_cadastrada()
    {
        $user = User::factory()->create();
        $this->setAuthUser($user, 'api');

        $track = Track::factory()->create();

        $response = $this->json('DELETE', "/api/tracks/{$track->uuid}");

        $response->assertOk();

        $this->assertSoftDeleted('tracks', ['uuid' => $track->uuid]);
    }
}