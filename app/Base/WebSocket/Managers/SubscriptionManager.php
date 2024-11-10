<?php

namespace App\Base\WebSocket\Managers;

use Ratchet\ConnectionInterface;
use App\Base\WebSocket\Managers\Interfaces\ISubscriptionManager;

class SubscriptionManager implements ISubscriptionManager
{
    private array $subscriptionChannels = [];

    public function getSubscriptionsChannels(): array
    {
        return $this->subscriptionChannels;
    }

    public function setSubscriptionsChannels(int $resourceId, array $subscription): void
    {
        $this->subscriptionChannels[$resourceId] = $subscription;
    }

    public function getSubscriptionDrivers(): array
    {
        return array_filter($this->subscriptionChannels, function ($user) {
            return $user['channel_name'] === 'drivers';
        });
    }

    public function cleanup(ConnectionInterface $conn): void
    {
        // Remove subscriptions related to the resourceId
        unset($this->subscriptionChannels[$conn->resourceId]);
    }
}
