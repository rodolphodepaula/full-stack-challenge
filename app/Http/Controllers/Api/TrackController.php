<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Track\TrackService;
use App\Http\Resources\Track\ApiTrackCollection;

class TrackController extends Controller
{
    public function __construct(protected TrackService $svcTrack)
    {}

    public function search(string $isrc): ApiTrackCollection
    {
        $tracks = $this->svcTrack->getBySearchApi($isrc);

        return new ApiTrackCollection(collect($tracks));
    }
}
