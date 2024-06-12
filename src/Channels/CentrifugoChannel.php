<?php

declare(strict_types=1);

namespace Anik\Laravel\Centrifugo\Channels;

use Anik\Laravel\Centrifugo\CentrifugoManager;
use Illuminate\Notifications\Notification;

class CentrifugoChannel
{
    protected CentrifugoManager $manager;

    public function __construct(CentrifugoManager $manager)
    {
        $this->manager = $manager;
    }

    public function send($notifiable, Notification $notification)
    {
    }

    protected function getData($notifiable, Notification $notification): array
    {
        return [];
    }
}
