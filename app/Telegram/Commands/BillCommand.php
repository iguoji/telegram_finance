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
    public function execute(string $argument = null) : mixed
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $this->getUpdate()->getChatId(),
            'text'          =>  '显示账单!' . PHP_EOL .
                                '详细信息可以查看[官网介绍](' . config('app.url') . ')!',
            'parse_mode'    =>  'Markdown',
        ];

        // 发送消息
        return $this->robot->sendMessage($context);
    }
}
