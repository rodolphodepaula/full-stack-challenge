<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Track\TrackRequest;
use App\Services\Track\TrackService;
use App\Http\Resources\Track\ApiTrackCollection;
use App\Http\Resources\Track\TrackCollection;
use App\Http\Resources\Track\TrackJson;
use App\Models\Track;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function __construct(protected TrackService $svcTrack)
    {}

    public function search(string $isrc): ApiTrackCollection
    {
        $tracks = $this->svcTrack->getBySearchApi($isrc);

        return new ApiTrackCollection(collect($tracks));
    }

    public function index(Request $request): TrackCollection
    {
        $filters =  [];
        $filters['search'] = $request->input('search') ?? '';
        $tracksQuery  = Track::query();
        $tracksQuery  = $this->svcTrack->getBySearch($tracksQuery, $filters);
        $tracks = $tracksQuery->whereCompanyIsActive()->orderBy('tracks.name', 'ASC');
        $perPage = $request->input('per_page', 10);

        return new TrackCollection($tracks->paginate($perPage));
    }

    public function store(TrackRequest $request): TrackJson
    {
        $track = $this->svcTrack->save($request->validated());

        return new TrackJson($track);
    }

    public function show(string $uuid): TrackJson
    {
        $track = Track::whereUuid($uuid)->firstOrFail();
        return new TrackJson($track);
    }

    public function update(TrackRequest $request, string $uuid): TrackJson
    {
        $track = Track::whereUuid($uuid)->firstOrFail();
        $track = $this->svcTrack->update($track, $request->validated());

        return new TrackJson($track);

    }

    public function destroy(string $uuid): TrackJson
    {
        $track = Track::whereUuid($uuid)->firstOrFail();
        $this->svcTrack->delete($track);

        return new TrackJson($track);
    }
}
