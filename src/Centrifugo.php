<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo;

use Anik\Laravel\Centrifugo\Contacts\ConnectionToken;
use Anik\Laravel\Centrifugo\Contacts\SubscriptionToken;
use Anik\Laravel\Centrifugo\Exception\CentrifugoException;
use Illuminate\Contracts\Auth\Authenticatable;

class Centrifugo
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function connectionToken(?Authenticatable $user): ?string
    {
        $provider = app()->make($this->config['token']['connection']['provider']);
        if (!$provider instanceof ConnectionToken) {
            throw new CentrifugoException('Connection token provider must be an instance of ' . ConnectionToken::class);
        }

        return $provider->token($user, $this->config);
    }

    public function subscriptionToken(?Authenticatable $user, ?string $channel): ?string
    {
        $provider = app()->make($this->config['token']['subscription']['provider']);
        if (!$provider instanceof SubscriptionToken) {
            throw new CentrifugoException('Subscription token provider must be an instance of ' . SubscriptionToken::class);
        }

        return $provider->token($user, $this->config, $channel);
    }
}
