<?php

use App\Telegram\Callbacks\TrialCallback;
use App\Telegram\Commands\StartCommand;

return [
    'bots' => [
        'zidongjizhang_bot'         =>  [
            'token'                 =>  '6385946365:AAH-_mTWIIB4Gt9h529UKUhrntXt4V70Hmk',
            'username'              =>  'zidongjizhang_bot',

            'commands'              =>  [
                '/start'            =>  StartCommand::class,
            ],
            'callbacks'             =>  [
                'trial'             =>  TrialCallback::class,
            ],
        ],
    ],
];
