<?php

namespace App\Telegram\Commands;

use App\Telegram\Command;

class StartCommand extends Command
{
    /**
     * 执行命令
     */
    public function execute(array $arguments = []): void
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $this->update['message']['chat']['id'] ?? 1234,
            'text'          =>  '我是自动记账机器人!' . PHP_EOL .
                                '详细信息可以查看[官网介绍](' . config('app.url') . ')!',
            'parse_mode'    =>  'Markdown',
            'reply_markup'  =>  json_encode([
                'inline_keyboard'       =>  [
                    [
                        ['text' => '点击试用3小时', 'callback_data' => 'trial'],
                    ],
                    [
                        ['text' => '拉我进群', 'url' => 'https://t.me/' . $this->robot->username . '?startgroup=botstart'],
                    ],
                ],
            ]),
        ];

        // 发送消息
        $this->robot->sendMessage($context);
    }
}
