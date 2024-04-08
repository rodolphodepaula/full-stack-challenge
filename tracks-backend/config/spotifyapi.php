<?php

return [
    'url' => env('SPOTIFY_API_URL', 'https://api.spotify.com/v1'),
    'url_token' => env('SPOTIFY_API_URL_TOKEN', 'https://accounts.spotify.com/api/token'),
    'client_id' => env('SPOTIFY_CLIENT_ID', ''),
    'client_secret' => env('SPOTIFY_CLIENT_SECRET', ''),
];
