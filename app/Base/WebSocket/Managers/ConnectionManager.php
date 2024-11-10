<?php
namespace App\Base\WebSocket\Managers;

use App\Base\WebSocket\Managers\Interfaces\IConnectionManager;
use App\Base\WebSocket\Services\Interfaces\IAuthService;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

class ConnectionManager implements IConnectionManager
{
    private SplObjectStorage $connections;

    public function __construct()
    {
        $this->connections = new SplObjectStorage;
    }

    public function attach(ConnectionInterface $conn): void
    {
        $this->connections->attach($conn);
    }

    public function detach(ConnectionInterface $conn): void
    {
        $this->connections->detach($conn);
    }

    public function getConnections(): SplObjectStorage
    {
        return $this->connections;
    }

    public function validateConnection(ConnectionInterface $conn, IAuthService $authService): bool
    {
        $query = $conn->httpRequest->getUri()->getQuery();
        parse_str($query, $queryParams);

        if (isset($queryParams['api-key'])) {
            if (!$authService->checkApiKey($queryParams['api-key'])) {
                $conn->send(json_encode(['error' => "Invalid API key"]));
                echo "Invalid API key\n";
                $conn->close();
                return false;
            }
        } else {
            $conn->send(json_encode(['error' => "API key is required"]));
            echo "API key is required\n";
            $conn->close();
            return false;
        }

        return true;
    }
}
