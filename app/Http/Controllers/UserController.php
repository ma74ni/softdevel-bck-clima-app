<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WeatherService;
use App\Events\WeatherUpdated;

class UserController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }
    /*
    * Retrieve all users and their corresponding weather data.
    * 
    * This method fetches all users from the database and maps their data 
    * with the current weather conditions based on their latitude and longitude.
    * The weather information is retrieved using the WeatherService.
    * 
    * @return JSON response containing the users and their weather data.
    */
    public function show()
    {
        $users = User::all()->map(function ($user) {
            $weather = $this->weatherService->getWeather($user->latitude, $user->longitude);
            $weatherData = [
                'id' => $weather['weather'][0]['id'] ?? 'No disponible',
                'main' => $weather['weather'][0]['main'] ?? 'No disponible',
                'description' => $weather['weather'][0]['description'] ?? 'No disponible',
                'icon' => $weather['weather'][0]['icon'] ?? 'No disponible',
                'temp' => $weather['main']['temp'] ?? 'No disponible',
                'feels_like' => $weather['main']['feels_like'] ?? 'No disponible',
                'temp_min' => $weather['main']['temp_min'] ?? 'No disponible',
                'temp_max' => $weather['main']['temp_max'] ?? 'No disponible',
                'countryName' => $weather['name'] ?? 'No disponible',
            ];
            broadcast(new WeatherUpdated($user, $weatherData));
            return [
                'name' => $user->name,
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
                'weather' => $weatherData,
            ];
        });
        return response()->json($users);
    }
}
