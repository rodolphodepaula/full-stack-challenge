<?php

namespace Database\Factories;

use App\Models\Album;
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
            'title' => $this->faker->sentence,
            'album_id' => Album::factory(),
            'isrc' => $this->faker->bothify('##????##'),
            'release_date' => $this->faker->date,
            'duration' => $this->faker->numberBetween(180, 300),
            'spotify_url' => $this->faker->url,
            'available_in_brazil' => $this->faker->boolean,
            // 'artist_id' => Artist::factory(),
        ];
    }
}
