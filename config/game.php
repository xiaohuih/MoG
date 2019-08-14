<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Game URL
    |--------------------------------------------------------------------------
    |
    | This URL is used to connect with game server. You should set this to the root of
    | your game server so that it is used when control game command.
    |
    */
    'url' => env('GAME_URL', 'http://192.168.52.220:8020/gm'),

    'salt' => [
        'root' => env('SALT_ROOT', '/data/salt'),
    ],
];
