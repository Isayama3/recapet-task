<?php

namespace App\Base\WebSocket\Traits;

trait WebSocketResponseTrait
{
    /**
     * Send a success response to the WebSocket client.
     *
     * @param string $event The event name.
     * @param array $data The data to send.
     * @return array
     */
    protected function sendSuccessSubscribeResponse(int $socket_user_id, int $channel_id, string $channel_name): string | false
    {
        return json_encode([
            'status' => 'success',
            'socket_user_id' => $socket_user_id,
            'channel_id' => $channel_id,
            'channel_name' => $channel_name,
        ]);
    }

    /**
     * Send a success response to the WebSocket client.
     *
     * @param string $event The event name.
     * @param array $data The data to send.
     * @return array
     */
    protected function sendSuccessResponse(string $event, array $data = []): string | false
    {
        return json_encode([
            'status' => 'success',
            'event' => $event,
            'data' => $data,
        ]);
    }

    /**
     * Send an error response to the WebSocket client.
     *
     * @param string $event The event name.
     * @param string $message The error message.
     * @param int|null $code The error code (optional).
     * @return array
     */
    protected function sendErrorResponse(string $event, string $message, $data = [], int $code = null): string | false
    {
        $response = [
            'status' => 'error',
            'event' => $event,
            'message' => $message,
            'data' => $data,
        ];

        if ($code !== null) {
            $response['code'] = $code;
        }

        return json_encode($response);
    }

    /**
     * Send a custom response to the WebSocket client.
     *
     * @param string $status The status of the response ('success', 'error', etc.).
     * @param string $event The event name.
     * @param array $data The custom data to send.
     * @return array
     */
    protected function sendCustomResponse(string $status, string $event, array $data = []): array
    {
        return [
            'status' => $status,
            'event' => $event,
            'data' => $data,
        ];
    }
}
