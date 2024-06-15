<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo\Broadcasters;

use Anik\Laravel\Centrifugo\CentrifugoManager;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        $channel = $request->channel_name;

        if (empty($channel)) {
            throw new AccessDeniedHttpException();
        }

        return parent::verifyUserCanAccessChannel(
            $request, $channel
        );
    }

    public function validAuthenticationResponse($request, $result)
    {
        $user = $this->retrieveUser($request, $channel = trim($request->channel_name));

        $token = $this->manager->connection($this->connection)->subscriptionToken($user, $channel);

        if (is_null($token)) {
            throw new AccessDeniedHttpException();
        }

        return response()->json([
            'token' => $token,
        ]);
    }

    public function broadcast(array $channels, $event, array $payload = [])
    {
        $this->manager->connection($this->connection)->broadcast($channels, ['event' => $event, 'data' => $payload]);
    }

    public function resolveAuthenticatedUser($request)
    {
        $user = parent::resolveAuthenticatedUser($request);

        $token = $this->manager->connection($this->connection)->connectionToken($user);

        if (is_null($token)) {
            throw new AccessDeniedHttpException();
        }

        return response()->json([
            'token' => $token,
        ]);
    }
}
