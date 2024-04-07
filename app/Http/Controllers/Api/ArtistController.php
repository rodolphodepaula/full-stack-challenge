<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Artist\ArtistRequest;
use App\Http\Resources\Artist\ArtistCollection;
use App\Http\Resources\Artist\ArtistJson;
use App\Models\Artist;
use App\Services\Artist\ArtistService;

class ArtistController extends Controller
{
    public function __construct(private ArtistService $srvArtist){}

    public function index(ArtistRequest $request): ArtistCollection
    {
        $filters = $request->validated();
        $artistQuery = Artist::query();
        $artistQuery = $this->srvArtist->getBySearch($artistQuery, $filters);
        $artists = $artistQuery->orderBy('artists.name', 'ASC');
        $perPage = $request->input('per_page', 10);

        return new ArtistCollection($artists->paginate($perPage));

    }

    public function store(ArtistRequest $request): ArtistJson
    {
        $artist = $this->srvArtist->save($request->validated());

        return new ArtistJson($artist);
    }

    public function show(string $uuid): ArtistJson
    {
        $artist = Artist::whereUuid($uuid)->firstOrFail();

        return new ArtistJson($artist);
    }

    public function update(ArtistRequest $request, string $uuid): ArtistJson
    {
        $artist = Artist::whereUuid($uuid)->firstOrFail();
        $artist = $this->srvArtist->update($artist, $request->validated());

        return new ArtistJson($artist);
    }

    public function destroy(string $uuid): ArtistJson
    {
        $artist = Artist::whereUuid($uuid)->firstOrFail();
        $this->srvArtist->delete($artist);

        return new ArtistJson($artist);
    }
}
