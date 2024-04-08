<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SpotifyIntegrationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_spotify_search()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Http::fake([
            'https://api.spotify.com/v1/*' => Http::response([
                'data' => [
                    [
                        'album_thumb' => "https://link.to.album/thumb1",
                        'release_date' => "2022-09-29",
                        'track_title' => "Blinding Lights",
                        'artists' => ["The Weeknd"],
                        'duration' => "03:21",
                        'preview_url' => null,
                        'spotify_url' => "https://open.spotify.com/track/trackid1",
                        'available_in_br' => false,
                    ],
                    [
                        'album_thumb' => "https://link.to.album/thumb2",
                        'release_date' => "2023-10-20",
                        'track_title' => "Blinding Lights",
                        'artists' => ["The Weeknd"],
                        'duration' => "03:21",
                        'preview_url' => null,
                        'spotify_url' => "https://open.spotify.com/track/trackid2",
                        'available_in_br' => false,
                    ],
                ]
            ], 200)
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer token_simulado_para_teste',
        ])->json('GET', 'http://localhost/api/tracks/search/USUG11904206');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'tracks' => [
                    '*' => [
                        'id', 'name', 'preview_url', 'artists' => [
                            '*' => ['name']
                        ]
                    ]
                ]
            ]);
    }
}
