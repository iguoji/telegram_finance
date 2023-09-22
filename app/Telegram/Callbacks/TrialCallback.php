<?php

namespace App\Telegram\Callbacks;

use App\Models\User;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TrialCallback extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 已有权限
        $date = Cache::get('telegram:trial:' . $this->getUpdate()->getFromId());
        if ($date && strtotime($date) > time()) {
            // 发送消息
            return $this->expired($date);
        }

        // 查询用户
        $user = User::where('uid', $this->getUpdate()->getFromId())->first();
        if (!empty($user)) {
            // 没有申请过
            if (empty($user['trial_at'])) {
                // 更新时间
                $user->trial_at = date('Y-m-d H:i:s', time() + $this->getRobot()->trial_expire);
                $user->saveOrFail();
                // 更新缓存
                Cache::forever('telegram:trial:' . $this->getUpdate()->getFromId(), $user->trial_at);
                // 发送消息
                return $this->success($user->trial_at);
            } else {
                // 发送消息
                return $this->expired($user['trial_at']);
            }
        }

        // 返回结果
        return $this->unknow();
    }

    

    /**
     * 申请成功
     */
    public function success(string $date) : mixed
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $this->getUpdate()->getChatId(),
            'text'          =>  '申请成功，到期时间：' . $date,
            'parse_mode'    =>  'Markdown',
        ];

        // 发送消息
        return $this->robot->sendMessage($context);
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
