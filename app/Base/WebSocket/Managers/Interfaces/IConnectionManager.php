<?php
namespace App\Base\WebSocket\Managers\Interfaces;

use App\Base\WebSocket\Services\Interfaces\IAuthService;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

interface IConnectionManager
{
    public function attach(ConnectionInterface $conn): void;

    public function detach(ConnectionInterface $conn): void;

    public function getConnections(): SplObjectStorage;

    public function validateConnection(ConnectionInterface $conn, IAuthService $authService): bool;
}
