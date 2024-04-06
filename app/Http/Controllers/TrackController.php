<?php

namespace App\Http\Controllers;

use App\Services\Track\TrackService;
use App\Http\Resources\Track\ApiTrackCollection;

class TrackController extends Controller
{
    public function __construct(protected TrackService $svcTrack)
    {}

    public function index(string $isrc): ApiTrackCollection
    {
        $tracks = $this->svcTrack->getBySearchApi($isrc);

        return new ApiTrackCollection(collect($tracks));
    }
}
