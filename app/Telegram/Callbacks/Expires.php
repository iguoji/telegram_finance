<?php

namespace App\Telegram\Callbacks;

use App\Telegram\Callback;
use Illuminate\Support\Facades\Cache;

class Expires extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 查看用户
        $user = Cache::get('telegram:user:' . $this->update->getFromId());
        if (!empty($user['vip_at'])) {
            // 发送消息
            return $this->send('你已有权限啦，结束时间：' . $user->vip_at);
        } else if (!empty($user['trial_at'])) {
            // 发送消息
            return $this->send('你已有权限啦，结束时间：' . $user->trial_at);
        } else {
            // 发送消息
            return $this->send('你还未有权限啦，可以试用或购买');
        }
    }
}
