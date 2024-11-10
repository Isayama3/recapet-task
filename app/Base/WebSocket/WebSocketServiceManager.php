<?php

namespace App\Base\WebSocket;

use App\Base\Traits\Request\SendRequest;
use App\Base\WebSocket\Managers\Interfaces\IConnectionManager;
use App\Base\WebSocket\Managers\Interfaces\ISubscriptionManager;
use App\Base\WebSocket\Managers\Interfaces\IUserManager;
use App\Base\WebSocket\Services\Interfaces\IAuthService;
use App\Base\WebSocket\Services\Interfaces\IEventFactory;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebSocketServiceManager implements MessageComponentInterface
{
    use SendRequest;

    private IConnectionManager $connectionManager;
    private ISubscriptionManager $subscriptionManager;
    private IUserManager $userManager;
    private $authService;
    private $eventFactory;

    public function __construct(
        IAuthService $authService,
        IEventFactory $eventFactory,
        IConnectionManager $connectionManager,
        ISubscriptionManager $subscriptionManager,
        IUserManager $userManager,
    ) {
        $this->connectionManager = $connectionManager;
        $this->subscriptionManager = $subscriptionManager;
        $this->userManager = $userManager;
        $this->authService = $authService;
        $this->eventFactory = $eventFactory;
        echo "Server Started \r\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        if (!$this->connectionManager->validateConnection($conn, $this->authService)) {
            return;
        }

        $this->connectionManager->attach($conn);
        echo "user " . $conn->resourceId . " connected \r\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->connectionManager->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected \r\n";
        $this->userManager->cleanup($conn);
        $this->subscriptionManager->cleanup($conn);
    }

    public function onMessage(ConnectionInterface $conn, $message)
    {
        // $message = str_replace(["\n", "\r"], '', $message);
        $data = json_decode($message);

        if (!isset($data->event)) {
            $conn->send(json_encode(['error' => "Event not recognized"]));
            return;
        }

        $event = $this->eventFactory->getEvent($data->event);

        if ($event) {
            $event->execute($conn, $data);
        } else {
            dump($event);
            $conn->send(json_encode(['error' => "Invalid Event"]));
        }
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()} \r\n";
        $conn->close();
    } 

    public function getSubscriptionManager(): ISubscriptionManager
    {
        return $this->subscriptionManager;
    }

    public function getUserManager(): IUserManager
    {
        return $this->userManager;
    }
}
