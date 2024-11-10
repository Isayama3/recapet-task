<?php

namespace App\Base\WebSocket\Services\Interfaces;

interface IEventFactory
{
    /**
     * Get an event by its type.
     *
     * @param string $event
     * @return mixed|null
     */
    public function getEvent(string $event);

    /**
     * Get a channel by its type.
     *
     * @param string $channelType
     * @return mixed|null
     */
    public function getChannel(string $channelType);
}
