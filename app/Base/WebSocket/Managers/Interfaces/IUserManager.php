<?php
namespace App\Base\WebSocket\Managers\Interfaces;

use Ratchet\ConnectionInterface;

interface IUserManager
{
    public function setWebSocketUser(int $resourceId, ConnectionInterface $conn): void;

    public function getWebSocketUsers(): array;

    public function setDBAuthUser(int $userId, array $user): void;

    public function getDBAuthUsers(): array;

    public function cleanup(ConnectionInterface $conn): void;
}
