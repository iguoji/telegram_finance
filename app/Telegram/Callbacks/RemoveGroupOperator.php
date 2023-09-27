<?php

namespace App\Telegram\Callbacks;

use App\Models\TelegramGroupOperator;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Log;

class RemoveGroupOperator extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 格式不对
        if (is_null($argument)) {
            return $this->send('格式不对，正确如下：' . PHP_EOL . '删除操作员 @usernanme');
        }

        // 用户名
        $usernames = explode(' ', $argument);
        $count = 0;
        foreach ($usernames as $index => $username) {
            // 有用户名
            if (str_starts_with($username, '@')) {
                $count += TelegramGroupOperator::where('group_id', $this->update->getChatId())->where('username', $username)->delete();
            } else {
                // 没有用户名
                $user = $this->update->getMessageEntities()[$index]['user'] ?? [];
                if (!empty($user)) {
                    // 已经存在
                    $count += TelegramGroupOperator::where('group_id', $this->update->getChatId())->where('user_id', $user['id'])->delete();
                }
            }
        }

        // 返回结果
        return $this->send('删除操作人完成，共删除 ' . $count . ' 位操作人');
    }
}
