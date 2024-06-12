<?php

use Anik\Laravel\Centrifugo\Contacts\ConnectionToken;
use Anik\Laravel\Centrifugo\Contacts\SubscriptionToken;

return [
    'default' => env('CENTRIFUGO_CONNECTION', 'centrifugo'),

    'connections' => [
        'centrifugo' => [
            'host' => env('CENTRIFUGO_HOST'),
            'port' => env('CENTRIFUGO_PORT'),
            'version' => env('CENTRIFUGO_VERSION', 'v5'),
            'secret_key' => env('CENTRIFUGO_SECRET_KEY'),
            'algorithm' => env('CENTRIFUGO_ALGORITHM', 'HS256'),
            'api_key' => env('CENTRIFUGO_API_KEY'),
            'token' => [
                'connection' => [
                    'expiry' => env('CENTRIFUGO_CONNECTION_TOKEN_EXPIRY'),
                    'provider' => ConnectionToken::class,
                    'allow_anonymous' => env('CENTRIFUGO_CONNECTION_ALLOW_ANONYMOUS', false),
                ],
                'subscription' => [
                    'expiry' => env('CENTRIFUGO_SUBSCRIPTION_TOKEN_EXPIRY'),
                    'provider' => SubscriptionToken::class,
                ],
            ],
        ],
    ],
];
