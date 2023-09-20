<?php

namespace App\Telegram\Callbacks;

use App\Telegram\Callback;

class TrialCallback extends Callback
{
    /**
     * 名称
     */
    protected $name = 'trial';

    /**
     * 描述
     */
    protected $description = 'Trial Callback';

    /**
     * 命令
     */
    protected $usage = 'trial';

    /**
     * 执行命令
     */
    public function execute(array $arguments = []): void
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $this->update['callback_query']['message']['chat']['id'] ?? 1234,
            'text'          =>  '正在帮您申请试用资格，请稍等!' . PHP_EOL .
                                '----',
            'parse_mode'    =>  'Markdown',
        ];

        // 发送消息
        $this->robot->sendMessage($context);
    }
}
