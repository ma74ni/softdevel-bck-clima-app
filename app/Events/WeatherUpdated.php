<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WeatherUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $weatherData;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, array $weatherData)
    {
        $this->user = $user;
        $this->weatherData = $weatherData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * Returns an instance of the channel on which the event will be broadcast.
     */
    public function broadcastOn()
    {
        return new Channel('weather-updates');
    }
}
