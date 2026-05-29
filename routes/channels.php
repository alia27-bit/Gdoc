<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('document.{uuid}', function ($user, $uuid) {
    return [
        'id' => $user->id,
        'name' => $user->name,
        'cursor_color' => $user->cursor_color ?? '#3b82f6',
    ];
});
