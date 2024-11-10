<?php

namespace App\Base\WebSocket\Events\SubscribeEvents;

use App\Base\WebSocket\Channels\Channel;

use App\Base\WebSocket\Events\Event;
use App\Base\WebSocket\Services\Interfaces\IAuthService;
use App\Base\WebSocket\Services\Interfaces\IEventFactory;
use App\Base\WebSocket\WebSocketServiceManager;
use Ratchet\ConnectionInterface;

class SubscribeEvent extends Event
{
    private WebSocketServiceManager $webSocketServiceManager;
    private IAuthService $authService;
    private IEventFactory $eventFactory;

    public function __construct(
        WebSocketServiceManager $webSocketServiceManager,
        IAuthService $authService,
        IEventFactory $eventFactory
    ) {
        $this->webSocketServiceManager = $webSocketServiceManager;
        $this->authService = $authService;
        $this->eventFactory = $eventFactory;
    }

    public function execute(ConnectionInterface $conn, $data)
    {
        if (!$this->isAuthenticated($conn, $data)) {
            return;
        }

        $strategy = $this->eventFactory->getChannel($data->channel_name);

        if (!$strategy || !$strategy->validate($data)) {
            $conn->send(json_encode(['error' => "Invalid or unauthorized subscription details"]));
            return;
        }

        $strategy->subscribe($conn, $data);

        $this->storeSubscription($conn, $data, $strategy);
    }

    private function isAuthenticated(ConnectionInterface $conn, $data): bool
    {
        if (!isset($data->user_data->auth_token, $data->user_data->user_type)) {
            $conn->send(json_encode(['error' => "Authentication required"]));
            return false;
        }

        $dbAuthUser = $this->authService->authenticateUser($data->user_data->auth_token, $data->user_data->user_type);
        if (!$dbAuthUser) {
            $conn->send(json_encode(['error' => "Invalid or expired token"]));
            return false;
        }

        $this->webSocketServiceManager->getUserManager()->setDBAuthUser($conn->resourceId, $dbAuthUser);
        return true;
    }

    private function storeSubscription(ConnectionInterface $conn, $data, Channel $strategy)
    {
        $this->webSocketServiceManager->getSubscriptionManager()->setSubscriptionsChannels($conn->resourceId, [
            'channel_id' => $data->channel_id,
            'channel_name' => $data->channel_name,
        ]);

        $this->webSocketServiceManager->getUserManager()->setWebSocketUser($conn->resourceId, $conn);
    }
}
