<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Telegram\Command;
use Illuminate\Support\Facades\Log;

class StartCommand extends Command
{
    /**
     * 是否需要注册
     */
    public $is_registered = false;

    /**
     * 执行命令
     */
    public function execute(string $text = '', array $user = [], array $chat = [], array $message = []): void
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $chat['id'] ?? 1234,
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
        if ($chat['type'] == 'private') {
            // 发送空消息、设置键盘
            $this->robot->sendMessage([
                'chat_id'       =>  $chat['id'] ?? 1234,
                'text'          =>  date('Y-m-d H:i:s'),
            ]);
        }

        // 注册用户
        if (!empty($user)) {
            // 调整参数
            $uid = $user['id'];
            unset($user['id']);
            $user['uid'] = $uid;
            // 创建保存
            $userObj = User::firstOrCreate($user);
            $userObj->save();
        }
    }
}
