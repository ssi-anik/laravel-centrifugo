<?php

namespace Anik\Laravel\Centrifugo\Contacts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notification;

interface Centrifugo
{
    public function connectionToken(?Authenticatable $user): ?string;

    public function subscriptionToken(?Authenticatable $user, ?string $channel): ?string;

    public function send($notifiable, Notification $notification);

    public function broadcast(array $channels, array $data): void;
}
