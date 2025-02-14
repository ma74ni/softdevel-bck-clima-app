<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('WEATHER_API_KEY'); // Define esto en tu .env
        $this->baseUrl = 'https://api.openweathermap.org/data/2.5/weather';
    }

    public function getWeather(float $latitude, float $longitude): array
    {
        try {
            $response = Http::timeout(5)->withOptions(["verify" => false])->get($this->baseUrl, [
                'lat' => $latitude,
                'lon' => $longitude,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'es',
            ]);
            if ($response->successful()) {
                return $response->json();
            }
            return ['error' => 'No se pudo obtener el clima'];
        } catch (\Exception $e) {
            Log::error('Error fetching weather data: ' . $e->getMessage());
            return ['Error en la conexión con la API'];
        }
    }
}
