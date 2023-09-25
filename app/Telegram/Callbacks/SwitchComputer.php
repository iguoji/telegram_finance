<?php

namespace App\Telegram\Callbacks;

use App\Telegram\Callback;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SwitchComputer extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // // 获取开关
        // $cacheKey = 'telegram:computer:' . $this->getUpdate()->getFromId();
        // $switch = Cache::get($cacheKey);
        // if (empty($switch) || $switch == 0 || $switch == '0') {
        //     // 开启计算
        //     Cache::forever($cacheKey, 1);
        //     // 返回结果
        //     return $this->unknow('已开启计算功能');
        // } else {
        //     // 关闭计算
        //     Cache::forever($cacheKey, 0);
        //     // 返回结果
        //     return $this->unknow('已关闭计算功能');
        // }
        return true;
    }
}
