<?php

namespace App\Telegram\Commands;

use App\Telegram\Command;
use Illuminate\Support\Facades\Log;

class AllBillCommand extends Command
{
    /**
     * 名称
     */
    public $name = 'allbill';

    /**
     * 描述
     */
    public $description = '完整账单，详细的查看账单明细!';

    /**
     * 命令
     */
    public $usage = '/allbill';

    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $this->getUpdate()->getChatId(),
            'text'          =>  '完整账单!' . PHP_EOL .
                                '详细信息可以查看[官网介绍](' . config('app.url') . ')!',
            'parse_mode'    =>  'Markdown',
        ];

        // 发送消息
        return $this->robot->sendMessage($context);
    }
}
