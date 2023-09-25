<?php

namespace App\Telegram\Callbacks;

use App\Telegram\Callback;
use Illuminate\Support\Facades\Log;

class Bill extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        return $this->send(
            '账单!' . PHP_EOL .
            '详细信息可以查看[官网介绍](' . config('app.url') . ')!', 
            [
                'parse_mode'    =>  'Markdown',
            ]
        );
    }
}
