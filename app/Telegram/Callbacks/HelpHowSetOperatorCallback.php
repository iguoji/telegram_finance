<?php

namespace App\Telegram\Callbacks;

use App\Telegram\Callback;
use Illuminate\Support\Facades\Log;

class HelpHowSetOperatorCallback extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 返回结果
        return $this->unknow('群内发：设置操作人 @xxxxx' . PHP_EOL . '先打空格再打@，会弹出选择更方便。');
    }
}
