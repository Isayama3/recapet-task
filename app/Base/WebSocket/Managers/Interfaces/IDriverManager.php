<?php
namespace App\Base\WebSocket\Managers\Interfaces;

interface IDriverManager
{
    public function setDrivers(int $resourceId, int $driver_id, string $name, int $phone, string $vehicle_number, float $latitude, float $longitude, string $timestamp): void;

    public function getDrivers(): array;

    public function getNearbyDrivers(float $pickup_latitude, float $pickup_longitude, $radius);
}
