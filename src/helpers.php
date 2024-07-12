<?php

use Firebase\JWT\JWT;

function generateToken($userId) {
    $key = 'your_secret_key';
    $payload = [
        'iss' => 'your_domain.com',
        'aud' => 'your_domain.com',
        'iat' => time(),
        'exp' => time() + (12 * 60 * 60), // 12 hours expiration
        'userId' => $userId
    ];

    $jwt = JWT::encode($payload, $key, 'HS256');

    // Store in the database
    $expiresAt = date('Y-m-d H:i:s', $payload['exp']);
    $stmt = $this->get('db')->prepare("INSERT INTO auth (user_id, token, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $jwt, $expiresAt]);

    return $jwt;
}
