<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo\Versions\V5;

use Anik\Centrifugo\Methods\V5\Broadcast;
use Anik\Centrifugo\Methods\V5\Publish;
use Anik\Centrifugo\Server\V5;
use Anik\Laravel\Centrifugo\Versions\V4\Centrifugo as CentrifugoV4;

class Centrifugo extends CentrifugoV4
{
    protected function authorizationResolver(): string
    {
        return V5::class;
    }

    protected function getPublishClass(): string
    {
        return Publish::class;
    }

    protected function getBroadcastClass(): string
    {
        return Broadcast::class;
    }
}
