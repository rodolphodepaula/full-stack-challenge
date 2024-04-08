<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Track;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrackFactory extends Factory
{
    protected $model = Track::class;

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'album_id' => Album::factory(),
            'isrc' => $this->faker->bothify('##????##'),
            'release_date' => $this->faker->date,
            'duration' => $this->faker->numberBetween(180, 300),
            'spotify_url' => $this->faker->url,
            'preview_url' => 'https://p.scdn.co/mp3-preview/' . $this->faker->regexify('[A-Za-z0-9]{22}'),
            'available_in_brazil' => $this->faker->boolean,
        ];
    }

    public function withArtists()
    {
        return $this->afterCreating(function (Track $track) {
            $artists = Artist::factory()->count(rand(1, 3))->create();
            $track->artists()->attach($artists->pluck('id'));
        });
    }
}
