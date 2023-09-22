<?php

namespace App\Telegram\Callbacks;

use App\Telegram\Callback;

class TrialCallback extends Callback
{
    /**
     * 名称
     */
    public $name = 'trial';

    /**
     * 描述
     */
    public $description = 'Trial Callback';

    /**
     * 命令
     */
    public $usage = 'trial';

    /**
     * 执行命令
     */
    public function execute(string $text = '', array $user = [], array $chat = [], array $message = []): void
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $chat['id'] ?? 1234,
            'text'          =>  '正在帮您申请试用资格，请稍等!' . PHP_EOL .
                                '----',
            'parse_mode'    =>  'Markdown',
        ];

        // 发送消息
        $this->robot->sendMessage($context);
    }
}
