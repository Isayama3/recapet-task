<?php

namespace App\Base\WebSocket\Events;

use App\Base\WebSocket\Traits\WebSocketResponseTrait;
use Ratchet\ConnectionInterface;

abstract class Event {
    use WebSocketResponseTrait;
    
    abstract public function execute(ConnectionInterface $conn, $data);
}