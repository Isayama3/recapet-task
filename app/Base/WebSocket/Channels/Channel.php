<?php

namespace App\Base\WebSocket\Channels;

use App\Base\WebSocket\Traits\WebSocketResponseTrait;
use Ratchet\ConnectionInterface;

abstract class Channel
{
    use WebSocketResponseTrait;

    abstract public function validate($data): bool;

    public function subscribe(ConnectionInterface $conn, $data)
    {
        $conn->send($this->sendSuccessSubscribeResponse($conn->resourceId, $data->channel_id, $data->channel_name));
    }
}
