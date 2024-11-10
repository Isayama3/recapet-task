<?php

namespace App\Base\WebSocket\Enums;

enum Channels: string
{
    case CHAT = 'chat';

    public function getPayloadTransformer(){
        switch ($this) {
            case 'chat':
                // ChatPayloadTransformer::class;
                break;
            default:
                break;
        }
    }
}