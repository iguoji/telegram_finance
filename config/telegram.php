<?php

use App\Telegram\Callbacks\CancelKeyboard;
use App\Telegram\Callbacks\Expires;
use App\Telegram\Callbacks\HelpHowSetAdmin;
use App\Telegram\Callbacks\HelpHowSetOperator;
use App\Telegram\Callbacks\PlaceOrder15Day;
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
        'PlaceOrder15Day'   =>  PlaceOrder15Day::class,
        'PlaceOrder1Month'   =>  PlaceOrder15Day::class,
        'PlaceOrder3Month'   =>  PlaceOrder15Day::class,
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
    ],

    // 机器人列表
    'bots' => [

        // 自动记账机器人
        'zidongjizhang_bot'         =>  [

            // 密钥
            'token'                 =>  '6385946365:AAH-_mTWIIB4Gt9h529UKUhrntXt4V70Hmk',
            // 用户名
            'username'              =>  'zidongjizhang_bot',

            // 试用时间 单位：秒
            'trial_duration'          =>  3600 * 3,

            // USDT
            'trc20'                 =>  'TY8jNABVoReroGTuPhyb9RYhfNtyB3bb1p',
            'erc20'                 =>  '0xa2e96F36f797a758eeEc6F802E53d29aE80bda71',

            // 价格
            'prices'                =>  [
                // ''
            ],

            // 指令
            'commands'          =>  [
                '/help'             =>  Help::class,
                '/bill'             =>  Bill::class,
                '/allbill'          =>  AllBill::class,
            ],

            

            // 私聊
            'private'               =>  [
                // 默认阶段，比如刚刚关注
                'default'           =>  [
                    // 键盘
                    'keyboard'          =>  [
                        ['立即试用', '自助续费', '到期时间'],
                        ['详细使用说明书'],
                        ['如何设置权限人', '如何设置群内操作人'],
                        ['开启/关闭计算功能'],
                    ],
                    // 匹配
                    'matches'           =>  [
                        '/start', 'trial', '立即试用', '自助续费', '到期时间',
                        '详细使用说明书',
                        '如何设置权限人', '如何设置群内操作人',
                        '开启/关闭计算功能',
                        '取消键盘',
                        'PlaceOrder15Day', 'PlaceOrder1Month', 'PlaceOrder3Month',
                    ],
                ],
                // 阶段二，比如已经付费
                'premium'           =>  [
                    // 键盘
                    'keyboard'          =>  [
                        ['自助续费', '到期时间'],
                        ['详细使用说明书'],
                        ['如何设置权限人', '如何设置群内操作人'],
                        ['开启/关闭计算功能'],
                    ],
                    // 匹配
                    'matches'           =>  [
                        '自助续费', '到期时间', 
                        '详细使用说明书',
                        '如何设置权限人', '如何设置群内操作人',
                        '开启/关闭计算功能',
                        '取消键盘',
                        'PlaceOrder15Day', 'PlaceOrder1Month', 'PlaceOrder3Month',
                    ],
                ],
            ],

            // 群聊
            'group'                 =>  [
                // 默认阶段：比如群内的普通人
                'default'           =>  [
                    // 键盘
                    'keyboard'          =>  [],
                    // 匹配
                    'matches'           =>  [
                        '账单', '显示账单',
                    ],
                ],
                // 操作人
                'operator'          =>  [
                    // 键盘
                    'keyboard'          =>  [],
                    // 匹配
                    'matches'           =>  [
                        '下发*', '+*', '-*',
                        '账单', '显示账单',
                        '上班', '加班', '下班',
                    ],
                ],
                // 授权人
                'administrator'     =>  [
                    // 键盘
                    'keyboard'          =>  [],
                    // 匹配
                    'matches'           =>  [
                        '账单', '显示账单',
                        '上班', '加班', '下班',
                    ],
                ],
            ],
        ],

        'lufei668bot'               =>  [
            'token'                 =>  '6502382554:AAHoC2sKzRWL5ldpx5d45sXHYbYW75Gt7vk',
            'username'              =>  'lufei668bot',
            'trial_expire'          =>  3600 * 3,
            'trc20'                 =>  'TY8jNABVoReroGTuPhyb9RYhfNtyB3bb1p',
            'erc20'                 =>  '0xa2e96F36f797a758eeEc6F802E53d29aE80bda71',
            'commands'              =>  [],
            'matches'               =>  [],
        ],
        'yixiu_bot'                 =>  [
            'token'                 =>  '6676903886:AAH5hhGBV8KNnSnWVBcAgNWcSsSuCnP8HBo',
            'username'              =>  'yixiu_bot',
            'trial_expire'          =>  3600 * 3,
            'trc20'                 =>  'TY8jNABVoReroGTuPhyb9RYhfNtyB3bb1p',
            'erc20'                 =>  '0xa2e96F36f797a758eeEc6F802E53d29aE80bda71',
            'commands'              =>  [],
            'matches'               =>  [],
        ],
        'daifuzhushoubot'               =>  [
            'token'                 =>  '6334070220:AAFrSuNaNfBeKz1E_HV8V7fSwGMIhQjoofc',
            'username'              =>  'daifuzhushoubot',
            'trial_expire'          =>  3600 * 3,
            'trc20'                 =>  'TY8jNABVoReroGTuPhyb9RYhfNtyB3bb1p',
            'erc20'                 =>  '0xa2e96F36f797a758eeEc6F802E53d29aE80bda71',
            'commands'              =>  [],
            'matches'               =>  [],
        ],
    ],
];
