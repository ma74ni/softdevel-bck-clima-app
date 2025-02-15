<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\WeatherService;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Event;
use App\Events\WeatherUpdated;
use Illuminate\Support\Facades\App;

class UserControllerTest extends TestCase
{
    protected $weatherService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->weatherService = $this->createMock(WeatherService::class);
    }

    public function testShow()
    {
        Event::fake();
        $user = User::factory()->create([
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
        ]);


        $weatherData = [
            'weather' => [
                [
                    'id' => 800,
                    'main' => 'Clear',
                    'description' => 'clear sky',
                    'icon' => '01d',
                ],
            ],
            'main' => [
                'temp' => 15.0,
                'feels_like' => 14.0,
                'temp_min' => 10.0,
                'temp_max' => 20.0,
            ],
            'name' => 'New York',
        ];

        $this->weatherService->method('getWeather')
            ->willReturn($weatherData);

        $response = $this->get('/api/users');

        $response->assertStatus(200);


        $response->assertJsonStructure([
            [
                'name',
                'latitude',
                'longitude',
                'weather' => [
                    'id',
                    'main',
                    'description',
                    'icon',
                    'temp',
                    'feels_like',
                    'temp_min',
                    'temp_max',
                    'countryName',
                ],
            ],
        ]);

        Event::assertDispatched(WeatherUpdated::class);
    }
}
