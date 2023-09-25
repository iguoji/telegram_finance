<?php

namespace App\Telegram\Callbacks;

use App\Telegram\Callback;
use Illuminate\Support\Facades\Log;

class HelpHowSetAdmin extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        return $this->send('@' . $this->robot->username . PHP_EOL . '这个你复制给要授权的人，让他点进去，再点一下start(开始)，点击【试用】或【申请】。' . PHP_EOL . PHP_EOL . '然后联系卖家 ,然后告诉他要授权的名字，给予授权');
    }
}
