<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
class ApiToken
{
    public function getToken(): string
    {
        return Cache::remember('siga_api_token', 3600, function () {

            $response = Http::asForm()->post(
                config('services.siga.token_path'),
                [
                    'client_id'     => config('services.siga.client_id'),
                    'client_secret' => config('services.siga.client_secret'),
                    'refresh_token' => config('services.siga.refresh_token'),
                    'grant_type'    => config('services.siga.grant_type'),
                ]
            );

            if ($response->failed()) {
                throw new \Exception('Failed to retrieve SIGA token');
            }

           return $response->json()['access_token']; 
        });
    }
}
