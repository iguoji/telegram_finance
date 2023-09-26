<?php

use App\Telegram\Callbacks\CancelKeyboard;
use App\Telegram\Callbacks\Expires;
use App\Telegram\Callbacks\HelpHowSetAdmin;
use App\Telegram\Callbacks\HelpHowSetOperator;
use App\Telegram\Callbacks\PlaceOrder;
use App\Telegram\Callbacks\SelfPayment;
use App\Telegram\Callbacks\SwitchComputer;
use App\Telegram\Callbacks\Trial;
use App\Telegram\Callbacks\AllBill;
use App\Telegram\Callbacks\Bill;
use App\Telegram\Callbacks\Help;
use App\Telegram\Callbacks\Start;

return [

    // 钩子列表
    'hooks'       =>  [
        'https://www.zidongjizhang.com/api/telegram/hook',
        'https://nl.zidongjizhang.com/api/telegram/hook',
    ],

    // 可用回调
    'callbacks'                     =>  [
        '/start'            =>  Start::class,
        '开始'              =>  Start::class,

        '/help'             =>  Help::class,
        '帮助'               =>  Help::class,
        '帮助文档'           =>  Help::class,
        '详细使用说明书'      =>  Help::class,

        '如何设置权限人'      =>  HelpHowSetAdmin::class,
        '如何设置群内操作人'  =>  HelpHowSetOperator::class, 
        '开启/关闭计算功能'   =>  SwitchComputer::class,

        '/bill'             =>  Bill::class,
        '账单'               =>  Bill::class,
        '显示账单'           =>  Bill::class,

        '/allbill'          =>  AllBill::class,
        '完整账单'           =>  AllBill::class,
        '显示完整账单'       =>  AllBill::class,

        'trial'             =>  Trial::class,
        '试用'               =>  Trial::class,
        '立即试用'           =>  Trial::class,

        '自助续费'           =>  SelfPayment::class,
        'PlaceOrder'        =>  PlaceOrder::class,
        '到期时间'            =>  Expires::class,

        '下发'               =>  '',
        '+'                 =>  '',
        '-'                 =>  '',
        '*'                 =>  '',
        '/'                 =>  '',

        '取消键盘'          =>   CancelKeyboard::class,
    ],

    // 回调参数
    'parameters'                    =>  [
        '+'                         =>  [
            'pre'   =>  true,
            'suf'   =>  true,
        ],
        '-'                         =>  [
            'pre'   =>  true,
            'suf'   =>  true,
        ],
        '*'                         =>  [
            'pre'   =>  true,
            'suf'   =>  true,
        ],
        '/'                         =>  [
            'pre'   =>  true,
            'suf'   =>  true,
        ],
        '下发'                      =>  [
            'pre'   =>  false,
            'suf'   =>  true,
        ],
        'PlaceOrder'                =>  [
            'pre'   =>  false,
            'suf'   =>  true,
        ],
    ],

    // 机器人列表
    'bots' => [
        'zidongjizhang_bot'         =>  [
            'token'                 =>  '6385946365:AAH-_mTWIIB4Gt9h529UKUhrntXt4V70Hmk',
            'username'              =>  'zidongjizhang_bot',
        ],
        'lufei668bot'               =>  [
            'token'                 =>  '6502382554:AAHoC2sKzRWL5ldpx5d45sXHYbYW75Gt7vk',
            'username'              =>  'lufei668bot',
        ],
        'yixiu_bot'                 =>  [
            'token'                 =>  '6676903886:AAH5hhGBV8KNnSnWVBcAgNWcSsSuCnP8HBo',
            'username'              =>  'yixiu_bot',
        ],
        'daifuzhushoubot'               =>  [
            'token'                 =>  '6334070220:AAFrSuNaNfBeKz1E_HV8V7fSwGMIhQjoofc',
            'username'              =>  'daifuzhushoubot',
        ],
    ],

    // 订单
    'order'                         =>  [
        // 超时时间，单位秒
        'timeout'                   =>  3600 * 2,
    ],

    // 波场
    'tron'                          =>  [
        'keys'                      =>  [
            'f4c13a8b-5ee5-47cd-8f86-379f208ae9d7',
            '6de39d58-9d38-486c-8851-558ec7e124f0',
            'ad00f121-98cf-4608-9e6f-038a98820332',
        ],
    ],
];
