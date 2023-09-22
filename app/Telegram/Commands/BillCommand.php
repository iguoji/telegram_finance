<?php

namespace App\Telegram\Commands;

use App\Telegram\Command;
use Illuminate\Support\Facades\Log;

class BillCommand extends Command
{
    /**
     * 名称
     */
    public $name = 'bill';

    /**
     * 描述
     */
    public $description = '显示账单，以简约的方式看看账单!';

    /**
     * 命令
     */
    public $usage = '/bill';

    /**
     * 执行命令
     */
    public function execute(string $text = '', array $user = [], array $chat = [], array $message = []): void
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $chat['id'] ?? 1234,
            'text'          =>  '显示账单!' . PHP_EOL .
                                '详细信息可以查看[官网介绍](' . config('app.url') . ')!',
            'parse_mode'    =>  'Markdown',
        ];

        // 发送消息
        $this->robot->sendMessage($context);
    }
}
