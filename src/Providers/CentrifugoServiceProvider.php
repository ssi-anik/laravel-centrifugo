<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CentrifugoServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
    }

    public function register()
    {
    }
}
