<?php

namespace App\Http\Controllers\Api;

use App\Models\Album;
use App\Http\Controllers\Controller;
use App\Http\Requests\Album\AlbumRequest;
use App\Http\Resources\Album\AlbumCollection;
use App\Http\Resources\Album\AlbumJson;
use App\Services\Album\AlbumService;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function __construct(private AlbumService $srvAlbum){}

    public function index(Request $request): AlbumCollection
    {
        $filters =  [];
        $filters['search'] = $request->input('search') ?? '';
        $albumQuery = Album::query();
        $albumQuery = $this->srvAlbum->getBySearch($albumQuery, $filters);
        $albums = $albumQuery->orderBy('albums.title', 'ASC');
        $perPage = $request->input('per_page', 10);

        return new AlbumCollection($albums->paginate($perPage));

    }

    public function store(AlbumRequest $request): AlbumJson
    {
        $album = $this->srvAlbum->save($request->validated());

        return new AlbumJson($album);
    }

    public function show(string $uuid): AlbumJson
    {
        $album = Album::whereUuid($uuid)->firstOrFail();

        return new AlbumJson($album);
    }

    public function update(AlbumRequest $request, string $uuid): AlbumJson
    {
        $album = Album::whereUuid($uuid)->firstOrFail();
        $album = $this->srvAlbum->update($album, $request->validated());

        return new AlbumJson($album);
    }

    public function destroy(string $uuid): AlbumJson
    {
        $album = Album::whereUuid($uuid)->firstOrFail();
        $this->srvAlbum->delete($album);

        return new AlbumJson($album);
    }
}
