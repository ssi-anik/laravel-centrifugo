<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo;

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

        return new Centrifugo($config);
    }

    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }
}
