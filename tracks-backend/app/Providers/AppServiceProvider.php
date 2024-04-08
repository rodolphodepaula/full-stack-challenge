<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Http::macro('spotifyapi', function () {
            $token = config('spotifyapi.url_token');
            $clientId = config('spotifyapi.client_id');
            $clientSecret = config('spotifyapi.client_secret');

            $response = Http::asForm()
                ->withBasicAuth($clientId, $clientSecret)
                ->post($token, [
                    'grant_type' => 'client_credentials'
                ]);

            $accessToken = $response['access_token'];
            return Http::acceptJson()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken
                ])->baseUrl(config('spotifyapi.url'))
                ->retry(3, 100);
        });
    }
}
