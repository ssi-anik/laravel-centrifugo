<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo\Broadcasters;

use Anik\Laravel\Centrifugo\CentrifugoManager;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;

class CentrifugoBroadcaster extends Broadcaster
{
    protected CentrifugoManager $manager;
    protected ?string $connection;

    public function __construct(CentrifugoManager $manager, ?string $connection = null)
    {
        $this->manager = $manager;
        $this->connection = $connection;
    }

    public function auth($request)
    {
    }

    public function validAuthenticationResponse($request, $result)
    {
    }

    public function broadcast(array $channels, $event, array $payload = [])
    {
    }

    public function resolveAuthenticatedUser($request)
    {
    }
}
