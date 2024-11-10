<?php

namespace App\Base\Services;

use Google\Client as GoogleClient;

class FirebaseHandler
{
    /**
     * Send notification
     *
     * @param array $tokens
     * @param string $title
     * @param string $body
     * @param string|null $icon_path
     * @param string|null $target_type
     * @param int|null $target_id
     * @return void
     */
    public function send(array $tokens, $title, $body, $icon_path = null, $target_type = null, $target_id = null)
    {

        // $fcmFields = [
        //     'registration_ids' => (array)$tokens,
        //     'priority' => 'high',
        //     'notification' => [
        //         'title' => $title,
        //         'body' => $body,
        //         'icon' => $icon_path,
        //         'target_type' => $target_type,
        //         'target_id' => $target_id
        //     ]
        // ];
        foreach ($tokens as $token) {
            $credentialsFilePath = public_path("json/fcm.json"); // local

            $client = new GoogleClient();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();

            $access_token = $token['access_token'];

            $headers = [
                "Authorization: Bearer $access_token",
                'Content-Type: application/json',
            ];

            $data = [
                "message" => [
                    "token" => $token,
                    "notification" => [
                        'title' => $title,
                        'body' => $body,
                    ],
                ],
            ];
            $payload = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/betaryaq/messages:send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                return response()->json([
                    'message' => 'Curl Error: ' . $err,
                ], 500);
            } else {
                return response()->json([
                    'message' => 'Notification has been sent',
                    'response' => json_decode($response, true),
                ]);
            }
        }

    }
}
