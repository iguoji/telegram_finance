<?php

namespace App\Telegram\Callbacks;

use App\Models\TelegramUser;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Trial extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 已有权限
        $user = Cache::get('telegram:user:' . $this->update->getFromId());
        if (!empty($user['trial_at']) && strtotime($user['trial_at']) > time()) {
            // 发送消息
            return $this->expired($user['trial_at']);
        }

        // 查询用户
        if (!empty($user)) {
            // 可试用时间
            $duration = $this->robot->robot->trial_duration ?? 0;
            if ($duration <= 0) {
                return $this->send('很抱歉、暂停试用申请！');
            }

            // 没有申请过
            if (empty($user['trial_at'])) {
                // 更新时间
                $user->trial_at = date('Y-m-d H:i:s', time() + $duration);
                $user->saveOrFail();
                // 更新缓存
                Cache::forever('telegram:trial:' . $user->id, $user->trial_at);
                // 发送消息
                return $this->success($user->trial_at);
            } else {
                // 发送消息
                return $this->expired($user->trial_at);
            }
        }

        // 返回结果
        return true;
    }

    /**
     * 申请成功
     */
    public function success(string $date) : mixed
    {
        return $this->send('申请成功，到期时间：' . $date);
    }

    /**
     * 到期了
     */
    public function expired(string $date) : mixed
    {
        return $this->send('你已有权限啦，结束时间：' . $date);
    }
}
