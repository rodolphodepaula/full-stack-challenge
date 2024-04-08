<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    public function definition(): array
    {
        $company = Company::factory()->create([
            'name' => $this->faker->company,
            'code' => 'ONERpm',
            'status' => Company::STATUS_ACTIVE,
        ]);

        $faker = \Faker\Factory::create();

        return [
            'uuid' => $this->faker->uuid,
            'title' => $faker->colorName . ' ' . $faker->word . ' ' . $faker->randomElement(['Dreams', 'Nights', 'Memories', 'Journey']),
            'thumb_path' => $this->faker->imageUrl(640, 480, 'music'),
            'company_id' => $company->id,
        ];
    }
}
