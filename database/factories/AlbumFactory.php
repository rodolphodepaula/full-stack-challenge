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

        return [
            'uuid' => $this->faker->uuid,
            'title' => $this->faker->title,
            'thumb_path' => $this->faker->imageUrl,
            'company_id' => $company->id,
        ];
    }
}
