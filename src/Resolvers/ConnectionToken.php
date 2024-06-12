<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo\Resolvers;

use Anik\Laravel\Centrifugo\Contacts\ConnectionToken as ConnectionTokenContract;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Authenticatable;

class ConnectionToken implements ConnectionTokenContract
{
    public function token(?Authenticatable $user, array $config): ?string
    {
        if (is_null($user) && true !== ($config['allow_anonymous'] ?? false)) {
            return null;
        }

        $key = $config['secret_key'];
        $algorithm = $config['algorithm'] ?? 'HS256';

        $subject = method_exists($user, 'centrifugoUserIdentifier')
            ? $user->centrifugoUserIdentifier()
            : $user->getAuthIdentifier();

        $payload = [
            'iat' => strtotime('now'),
            'sub' => $subject,
        ];

        $expiry = $config['token']['connection']['expiry'] ?? null;
        if (!is_null($expiry)) {
            $payload['exp'] = strtotime($expiry);
        }

        return JWT::encode($payload, $key, $algorithm);
    }
}
