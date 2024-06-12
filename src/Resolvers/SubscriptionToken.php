<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo\Resolvers;

use Anik\Laravel\Centrifugo\Contacts\SubscriptionToken as SubscriptionTokenContract;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Authenticatable;

class SubscriptionToken implements SubscriptionTokenContract
{
    public function token(?Authenticatable $user, array $config, ?string $channel): ?string
    {
        if (is_null($user)) {
            return null;
        }

        if (empty($channel)) {
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
            'channel' => $channel,
        ];

        $expiry = $config['expiry'] ?? null;
        if (!is_null($expiry)) {
            $payload['exp'] = strtotime($expiry);
        }

        return JWT::encode($payload, $key, $algorithm);
    }
}
