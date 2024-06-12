<?php

namespace Anik\Laravel\Centrifugo\Contacts;

use Illuminate\Contracts\Auth\Authenticatable;

interface ConnectionToken
{
    public function token(?Authenticatable $user, array $config): string;
}
