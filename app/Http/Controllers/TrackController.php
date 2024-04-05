<?php

namespace App\Http\Controllers;

use App\Services\TrackService;
use App\Http\Resources\Track\TrackCollection;

class TrackController extends Controller
{
    public function __construct(protected TrackService $svcTrack)
    {}

    public function index(string $isrc): TrackCollection
    {
        $tracks = $this->svcTrack->getBySearch($isrc);

        return new TrackCollection(collect($tracks));
    }
}
