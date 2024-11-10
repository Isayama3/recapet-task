<?php

namespace App\Base\WebSocket\Managers;

use Ratchet\ConnectionInterface;
use App\Base\WebSocket\Managers\Interfaces\IUserManager;

class UserManager implements IUserManager
{
    private array $websocketUsers = [];
    private array $dbAuthUsers = [];
    
    public function setWebSocketUser(int $resourceId, ConnectionInterface $conn): void
    {
        $this->websocketUsers[$resourceId] = $conn;
    }

    public function getWebSocketUsers(): array
    {
        return $this->websocketUsers;
    }

    public function setDBAuthUser(int $userId, array $user): void
    {
        $this->dbAuthUsers[$userId] = $user;
    }

    public function getDBAuthUsers(): array
    {
        return $this->dbAuthUsers;
    }

    public function cleanup(ConnectionInterface $conn): void
    {
        unset($this->websocketUsers[$conn->resourceId]);
        unset($this->rideRequests[$conn->resourceId]);
    }

}
