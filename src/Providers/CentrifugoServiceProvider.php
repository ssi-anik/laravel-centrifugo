<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo\Providers;

use Anik\Laravel\Centrifugo\Broadcasters\CentrifugoBroadcaster;
use Anik\Laravel\Centrifugo\CentrifugoManager;
use Anik\Laravel\Centrifugo\Channels\CentrifugoChannel;
use Anik\Laravel\Centrifugo\Contacts\ConnectionToken as ConnectionTokenContract;
use Anik\Laravel\Centrifugo\Contacts\SubscriptionToken as SubscriptionTokenContract;
use Anik\Laravel\Centrifugo\Resolvers\ConnectionToken;
use Anik\Laravel\Centrifugo\Resolvers\SubscriptionToken;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;

class CentrifugoServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->publishAndMergeConfig();
        $this->registerManagers();
        $this->registerFacades();
        $this->registerTokenResolvers();
        $this->extendBroadcasting();
        $this->extendNotification();
    }

    protected function publishAndMergeConfig(): void
    {
        $path = realpath(__DIR__ . '/../config/centrifugo.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([$path => config_path('centrifugo.php'),]);
        }

        $this->mergeConfigFrom($path, 'centrifugo');
    }

    protected function registerManagers(): void
    {
        $this->app->singleton(CentrifugoManager::class, fn($app) => new CentrifugoManager($app));
    }

    protected function registerFacades(): void
    {
        $this->app->bind('centrifugo', fn($app) => $app->make(CentrifugoManager::class));
    }

    protected function registerTokenResolvers(): void
    {
        $this->app->bind(ConnectionTokenContract::class, fn($app) => new ConnectionToken());
        $this->app->bind(SubscriptionTokenContract::class, fn($app) => new SubscriptionToken());
    }

    protected function extendBroadcasting(): void
    {
        $this->app->make(BroadcastManager::class)->extend('centrifugo', function (Container $app, array $config) {
            return new CentrifugoBroadcaster($app->make(CentrifugoManager::class), $config['connection']);
        });
    }

    protected function extendNotification(): void
    {
        $this->app->make(ChannelManager::class)->extend('centrifugo', function (Container $app) {
            return new CentrifugoChannel($app->make(CentrifugoManager::class));
        });
    }

    public function provides(): array
    {
        return [
            'centrifugo',
            CentrifugoManager::class,
            ConnectionTokenContract::class,
            SubscriptionTokenContract::class,
        ];
    }
}
