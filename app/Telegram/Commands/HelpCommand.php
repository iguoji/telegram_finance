<?php

namespace App\Telegram\Commands;

use App\Telegram\Command;
use Illuminate\Support\Facades\Log;

class HelpCommand extends Command
{
    /**
     * 名称
     */
    public $name = 'help';

    /**
     * 描述
     */
    public $description = '帮助文档，详细介绍了怎么使用该机器人！';

    /**
     * 命令
     */
    public $usage = '/help';

    /**
     * 执行命令
     */
    public function execute(string $text = '', array $user = [], array $chat = [], array $message = []): void
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $chat['id'] ?? 1234,
            'text'          =>  '帮助文档!' . PHP_EOL .
                                '详细信息可以查看[官网介绍](' . config('app.url') . ')!',
            'parse_mode'    =>  'Markdown',
        ];

        // 发送消息
        $this->robot->sendMessage($context);
    }
}
