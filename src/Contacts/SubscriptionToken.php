<?php

namespace Anik\Laravel\Centrifugo\Contacts;

use Illuminate\Contracts\Auth\Authenticatable;

interface SubscriptionToken
{
    public function token(?Authenticatable $user, array $config, ?string $channel): ?string;
}
