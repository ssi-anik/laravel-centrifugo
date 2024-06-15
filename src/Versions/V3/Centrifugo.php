<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo\Versions\V3;

use Anik\Centrifugo\Methods\V3\Broadcast;
use Anik\Centrifugo\Methods\V3\Publish;
use Anik\Centrifugo\Server\V3;
use Anik\Centrifugo\ServerApi;
use Anik\Laravel\Centrifugo\Contacts\Centrifugo as CentrifugoContract;
use Anik\Laravel\Centrifugo\Contacts\ConnectionToken;
use Anik\Laravel\Centrifugo\Contacts\SubscriptionToken;
use Anik\Laravel\Centrifugo\Exception\CentrifugoException;
use GuzzleHttp\Client;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;

class Centrifugo implements CentrifugoContract
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function authorizationResolver(): string
    {
        return V3::class;
    }

    protected function getPublishClass(): string
    {
        return Publish::class;
    }

    protected function getBroadcastClass(): string
    {
        return Broadcast::class;
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

        $config = [
            'secret_key' => $this->config['secret_key'],
            'algorithm' => $this->config['algorithm'] ?? 'HS256',
            'expiry' => $this->config['token']['connection']['expiry'] ?? null,
            'allow_anonymous' => $this->config['token']['connection']['allow_anonymous'] ?? false,
        ];

        return $provider->token($user, $config);
    }

    public function subscriptionToken(?Authenticatable $user, ?string $channel): ?string
    {
        $provider = app()->make($this->config['token']['subscription']['provider']);
        if (!$provider instanceof SubscriptionToken) {
            throw new CentrifugoException('Subscription token provider must be an instance of ' . SubscriptionToken::class);
        }

        $config = [
            'secret_key' => $this->config['secret_key'],
            'algorithm' => $this->config['algorithm'] ?? 'HS256',
            'expiry' => $this->config['token']['subscription']['expiry'] ?? null,
            'allow_anonymous' => $this->config['token']['subscription']['allow_anonymous'] ?? false,
        ];

        return $provider->token($user, $config, $channel);
    }

    public function send($notifiable, Notification $notification): bool
    {
        if (!method_exists($notifiable, 'routeNotificationForCentrifugo')) {
            return false;
        }

        $channels = Arr::wrap($notifiable->routeNotificationForCentrifugo($notifiable));

        $data = method_exists($notification, 'toCentrifugo')
            ? $notification->toCentrifugo($notifiable)
            : $notification->toArray($notifiable);

        if (empty($data)) {
            return false;
        }

        $this->broadcast($channels, $data);

        return true;
    }

    public function broadcast(array $channels, array $data): void
    {
        $broadcast = app($this->getBroadcastClass(), ['channels' => $channels, 'data' => $data]);
        $client = new Client(['base_uri' => $this->config['host'],]);

        (new ServerApi($client, $this->authorizationResolver()))->operation($broadcast);
    }

    public function publish(string $channel, array $data): void
    {
        $publish = app($this->getPublishClass(), ['channel' => $channel, 'data' => $data]);
        $client = new Client(['base_uri' => $this->config['host'],]);

        (new ServerApi($client, $this->authorizationResolver()))->operation($publish);
    }
}
