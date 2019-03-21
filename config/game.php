<?php

return [
    'gm' => [
        /*
        |--------------------------------------------------------------------------
        | Game URL
        |--------------------------------------------------------------------------
        |
        | This URL is used to connect with game server. You should set this to the root of
        | your game server so that it is used when control game command.
        |
        */
        'url' => env('GM_URL', 'http://127.0.0.1:8020/web')
    ],
    'account' => [
        /*
        |--------------------------------------------------------------------------
        | Game URL
        |--------------------------------------------------------------------------
        |
        | This URL is used to connect with game server. You should set this to the root of
        | your game server so that it is used when control game command.
        |
        */
        'url' => env('ACCOUNT_URL', 'http://127.0.0.1:8902/web')
    ],
];
