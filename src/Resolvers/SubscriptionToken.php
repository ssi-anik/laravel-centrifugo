<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo\Resolvers;

use Anik\Laravel\Centrifugo\Contacts\ConnectionToken as ConnectionTokenContract;
use Illuminate\Contracts\Auth\Authenticatable;

class SubscriptionToken implements ConnectionTokenContract
{
    public function token(?Authenticatable $user, array $config): string
    {
        return '';
    }
}
