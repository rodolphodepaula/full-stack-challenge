<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Company;
use App\Models\Track;
use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::firstOrCreate([
            'name' => 'ONERpm - Full Stack',
            'code' => 'ONERpm',
            'status' => Company::STATUS_ACTIVE,
        ]);

        Album::factory()->count(10)->create(['company_id' => $company->id])
            ->each(function ($album) {
                $artists = Artist::factory()->count(5)->create();
                $tracks = Track::factory()->count(5)->make();

                $tracks->each(function ($track) use ($album, $artists) {
                    $album->tracks()->save($track);

                    $track->artists()->attach(
                        $artists->random(rand(1, 3))->pluck('id')->toArray()
                    );
                });
            });
    }
}
