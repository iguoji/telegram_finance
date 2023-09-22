<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Telegram\Chat;
use App\Telegram\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class StartCommand extends Command
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $this->getUpdate()->getChatId(),
            'text'          =>  '我是自动记账机器人!' . PHP_EOL .
                                '详细信息可以查看[官网介绍](' . config('app.url') . ')!',
            'parse_mode'    =>  'Markdown',
            'reply_markup'  =>  [
                'inline_keyboard'       =>  [
                    [
                        ['text' => '点击试用3小时', 'callback_data' => 'trial'],
                    ],
                    [
                        ['text' => '拉我进群', 'url' => 'https://t.me/' . $this->robot->username . '?startgroup=botstart'],
                    ],
                ],
            ],
        ];

        // 发送消息
        $this->robot->sendMessage($context);

        // 如果是私聊
        if ($this->getUpdate()->getChatType() == Chat::TYPE_PRIVATE) {
            // 发送空消息、设置键盘
            $this->robot->sendMessage([
                'chat_id'       =>  $this->getUpdate()->getChatId(),
                'text'          =>  '-',
                'reply_markup'  =>  [
                    'keyboard'      =>  array_map(fn($r) => array_map(fn($v) => ['text' => $v], $r), $this->getConfig('keyboard')),
                ],
            ]);
        }

        // 注册用户
        if (empty(Cache::get('telegram:user:reg'))) {
            // 调整参数
            $user = $this->getUpdate()->getFrom();
            $uid = $user['id'];
            unset($user['id']);
            $user['uid'] = $uid;
            // 创建保存
            $userObj = User::firstOrCreate($user);
            $userObj->saveOrFail();
            // 更新缓存
            Cache::forever('telegram:user:reg', date('Y-m-d H:i:s'));
        }

        // 返回结果
        return '';
    }
}
