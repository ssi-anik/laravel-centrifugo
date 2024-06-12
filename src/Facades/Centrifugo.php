<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo\Facades;

use Illuminate\Support\Facades\Facade;

class Centrifugo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'centrifugo';
    }
}
