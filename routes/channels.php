<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Group;

Broadcast::channel('chat.{receiver}.{sender}', function ($user, $receiver, $sender) {
    return (int) $user->id === (int) $receiver || (int) $user->id === (int) $sender;
});

Broadcast::channel('group.{groupId}', function ($user, $groupId) {
    return $user->groups()->where('groups.id', $groupId)->exists();
});