<?php
namespace App\Base\WebSocket\Managers\Interfaces;

use Ratchet\ConnectionInterface;

interface IRideManager
{
    public function getRide();
    public function setRide(int $ride_id);
    public function getRideRequests(): array;
    public function setRideRequest(int $customer_id): void;
    public function getRideRequestsDrivers(int $customer_id): array;
    public function setRideRequestsDriver(int $customer_id,int $driver_id): void;
    public function cleanup(ConnectionInterface $conn): void;
}
