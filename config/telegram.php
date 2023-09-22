<?php

use App\Telegram\Callbacks\TrialCallback;
use App\Telegram\Commands\AllBillCommand;
use App\Telegram\Commands\BillCommand;
use App\Telegram\Commands\HelpCommand;
use App\Telegram\Commands\StartCommand;

return [
    'bots' => [
        'zidongjizhang_bot'         =>  [
            'token'                 =>  '6385946365:AAH-_mTWIIB4Gt9h529UKUhrntXt4V70Hmk',
            'username'              =>  'zidongjizhang_bot',

            'commands'              =>  [
                '/start'            =>  StartCommand::class,
                '/help'             =>  HelpCommand::class,
                '/bill'             =>  BillCommand::class,
                '/allbill'          =>  AllBillCommand::class,
            ],
            'callbacks'             =>  [
                'trial'             =>  TrialCallback::class,
                '试用'              =>  TrialCallback::class,
                '开始'              =>  StartCommand::class,
            ],
            'keyboard'              =>  [
                ['试用', '开始', '到期时间'],
                ['详细说明书', '自助续费'],
                ['如何设置权限人', '如何设置群内操作人'],
                ['开启/关闭计算功能'],
            ],
        ],
    ],
];
