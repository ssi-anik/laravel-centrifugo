<?php

namespace Anik\Laravel\Centrifugo;

class Centrifugo
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
