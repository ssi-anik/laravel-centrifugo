<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo;

use Anik\Laravel\Centrifugo\Contacts\Centrifugo;
use Anik\Laravel\Centrifugo\Versions\V3\Centrifugo as CentrifugoV3;
use Anik\Laravel\Centrifugo\Versions\V4\Centrifugo as CentrifugoV4;
use Anik\Laravel\Centrifugo\Versions\V5\Centrifugo as CentrifugoV5;
use Illuminate\Container\Container;
use InvalidArgumentException;

class CentrifugoManager
{
    protected Container $app;
    protected array $connections = [];

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function connection(?string $name = null): Centrifugo
    {
        $name = $name ?? config('centrifugo.default');

        return $this->connections[$name] ??= $this->resolve($name);
    }

    protected function resolve(string $name): Centrifugo
    {
        $config = config('centrifugo.connections.' . $name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Centrifugo connection [{$name}] is not defined.");
        }

        $version = $config['version'] ?? 'v5';
        if ($version === 'v3') {
            $class = CentrifugoV3::class;
        } elseif ($version === 'v4') {
            $class = CentrifugoV4::class;
        } else {
            $class = CentrifugoV5::class;
        }

        return app()->make($class, ['config' => $config]);
    }

    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }
}
