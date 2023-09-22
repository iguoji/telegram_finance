<?php

namespace App\Telegram\Callbacks;

use App\Models\User;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Cache;

class ExpiresCallback extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 查询用户
        $user = User::where('uid', $this->getUpdate()->getFromId())->first();
        if (!empty($user)) {
            // 具体时间
            $date = $user['vip_at'] ?: $user['trial_at'] ?: null;
            // 没有购买过
            if (empty($date)) {
                // 发送消息
                return $this->unknow('你还未有权限啦，可以试用或购买');
            } else {
                // 发送消息
                return $this->expired($date);
            }
        }

        // 返回结果
        return $this->unknow();
    }

    /**
     * 到期了
     */
    public function expired(string $date) : mixed
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $this->getUpdate()->getChatId(),
            'text'          =>  '你已有权限啦，结束时间：' . $date,
            'parse_mode'    =>  'Markdown',
        ];

        // 发送消息
        return $this->robot->sendMessage($context);
    }
}
