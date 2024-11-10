<?php

namespace App\Base\WebSocket\Services\Interfaces;

interface IAuthService
{
    public function checkApiKey(string $apiKey): bool;
    public function authenticateUser(string $authToken, string $userType): ?array;
    public function extractTokenId(string $accessToken): ?string;
    public function getUserDetails(object $token, string $userType): ?array;
}
