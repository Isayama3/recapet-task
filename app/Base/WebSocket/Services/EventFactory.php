<?php
// EventFactory.php
namespace App\Base\WebSocket\Services;

use App\Base\WebSocket\Channels\Channel;
use App\Base\WebSocket\Channels\ChatChannel;
use App\Base\WebSocket\Channels\DriversChannel;
use App\Base\WebSocket\Channels\RideChannel;
use App\Base\WebSocket\Channels\RideRequestChannel;
use App\Base\WebSocket\Events\ChatEvents\SendMessageEvent;
use App\Base\WebSocket\Events\DriverEvents\AcceptRide;
use App\Base\WebSocket\Events\DriverEvents\RejectRide;
use App\Base\WebSocket\Events\DriverEvents\SetDriverDataEvent;
use App\Base\WebSocket\Events\Event;
use App\Base\WebSocket\Events\RideRequestEvents\InitRideRequest;
use App\Base\WebSocket\Events\SubscribeEvents\SubscribeEvent;
use App\Base\WebSocket\Services\Interfaces\IEventFactory;

class EventFactory implements IEventFactory
{
    protected $channels = [];
    protected $events = [];

    public function __construct()
    {
        $this->loadChannels();
        $this->loadEvents();
    }

    private function loadChannels()
    {
        $channelClasses = [
            // 'chat' => ChatChannel::class,
            // Add new channels here
        ];

        foreach ($channelClasses as $type => $class) {
            if (is_subclass_of($class, Channel::class)) {
                $this->channels[$type] = $class;
            }
        }
    }

    private function loadEvents()
    {
        $event_classes = [
            'subscribe' => SubscribeEvent::class,
            // Add new events here
        ];

        foreach ($event_classes as $type => $class) {
            if (is_subclass_of($class, Event::class)) {
                $this->events[$type] = $class;
            }
        }
    }

    public function getEvent(string $event)
    {
        if (isset($this->events[$event])) {
            // Resolve the event class EventFactory the service container
            return app()->make($this->events[$event]);
        }

        return null;
    }

    public function getChannel(string $channelType)
    {
        if (isset($this->channels[$channelType])) {
            // Resolve the event class EventFactory the service container
            return app()->make($this->channels[$channelType]);
        }
    }
}
