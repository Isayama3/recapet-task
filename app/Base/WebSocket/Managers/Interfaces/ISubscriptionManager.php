<?php
namespace App\Base\WebSocket\Managers\Interfaces;

use Ratchet\ConnectionInterface;

interface ISubscriptionManager
{
    public function getSubscriptionsChannels(): array;

    public function setSubscriptionsChannels(int $resourceId, array $subscription): void;

    public function getSubscriptionDrivers(): array;

    public function cleanup(ConnectionInterface $conn): void;
}
