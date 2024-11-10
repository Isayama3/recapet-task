<?php

namespace App\Base\WebSocket\Services;

use App\Base\WebSocket\Services\Interfaces\IAuthService;
use Laravel\Passport\TokenRepository;

class AuthService implements IAuthService
{
    private $tokenRepository;

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function checkApiKey(string $apiKey): bool
    {
        return $apiKey === env('WEBSOCKET_API_KEY');
    }

    public function authenticateUser(string $auth_token, string $user_type): ?array
    {
        $db_token_id = $this->extractTokenId($auth_token);
        if (!$db_token_id) {
            return null;
        }

        $token = $this->tokenRepository->find($db_token_id);
        if ($token && !$token->revoked) {
            return $this->getUserDetails($token, $user_type);
        }

        return null;
    }

    public function extractTokenId(string $accessToken): ?string
    {
        $tokenParts = explode('.', explode(' ', $accessToken)[0]);
        if (count($tokenParts) < 2) {
            return null;
        }

        $tokenHeader = base64_decode($tokenParts[1]);
        $tokenHeaderArray = json_decode($tokenHeader, true);

        return $tokenHeaderArray['jti'] ?? null;
    }

    public function getUserDetails(object $token, string $user_type) : ?array
    {
        if ($token->client_id == 1 && $user_type == 'client') {
            return [
                'user_type' => 'client',
                'user_id' => $token->user_id,
            ];
        }

        return null;
    }
}
