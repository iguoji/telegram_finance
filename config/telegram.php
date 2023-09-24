<?php

use App\Telegram\Callbacks\CancelKeyboardCallback;
use App\Telegram\Callbacks\ExpiresCallback;
use App\Telegram\Callbacks\HelpHowSetAdminCallback;
use App\Telegram\Callbacks\HelpHowSetOperatorCallback;
use App\Telegram\Callbacks\PlaceOrder15DayCallback;
use App\Telegram\Callbacks\SelfPaymentCallback;
use App\Telegram\Callbacks\SwitchComputerCallback;
use App\Telegram\Callbacks\TrialCallback;
use App\Telegram\Commands\AllBillCommand;
use App\Telegram\Commands\BillCommand;
use App\Telegram\Commands\HelpCommand;
use App\Telegram\Commands\StartCommand;

return [

    // 服务器列表
    'servers'       =>  [
        'https://www.zidongjizhang.com/api/telegram/hook',
        'https://nl.zidongjizhang.com/api/telegram/hook',
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
            'trial_expire'          =>  3600 * 3,

            // USDT
            'trc20'                 =>  'TY8jNABVoReroGTuPhyb9RYhfNtyB3bb1p',
            'erc20'                 =>  '0xa2e96F36f797a758eeEc6F802E53d29aE80bda71',

            // 价格
            'prices'                =>  [
                // ''
            ],

            // 指令
            'commands'          =>  [
                '/help'             =>  HelpCommand::class,
                '/bill'             =>  BillCommand::class,
                '/allbill'          =>  AllBillCommand::class,
            ],

            // 匹配，*结尾表示有参数
            'matches'               =>  [
                '/start'            =>  StartCommand::class,
                '开始'              =>  StartCommand::class,

                '/help'             =>  HelpCommand::class,
                '帮助'               =>  HelpCommand::class,
                '帮助文档'           =>  HelpCommand::class,
                '详细使用说明书'      =>  HelpCommand::class,

                '如何设置权限人'      =>  HelpHowSetAdminCallback::class,
                '如何设置群内操作人'  =>  HelpHowSetOperatorCallback::class, 
                '开启/关闭计算功能'   =>  SwitchComputerCallback::class,

                '/bill'             =>  BillCommand::class,
                '账单'               =>  BillCommand::class,
                '显示账单'           =>  BillCommand::class,

                '/allbill'          =>  AllBillCommand::class,
                '完整账单'           =>  AllBillCommand::class,
                '显示完整账单'       =>  AllBillCommand::class,

                'trial'             =>  TrialCallback::class,
                '试用'               =>  TrialCallback::class,
                '立即试用'           =>  TrialCallback::class,

                '自助续费'           =>  SelfPaymentCallback::class,
                'PlaceOrder15Day'   =>  PlaceOrder15DayCallback::class,
                'PlaceOrder1Month'   =>  PlaceOrder15DayCallback::class,
                'PlaceOrder3Month'   =>  PlaceOrder15DayCallback::class,
                '到期时间'            =>  ExpiresCallback::class,

                '+*'                 =>  '',
                '下发*'               =>  '',
                '-*'                 =>  '',

                '取消键盘'          =>   CancelKeyboardCallback::class,
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
