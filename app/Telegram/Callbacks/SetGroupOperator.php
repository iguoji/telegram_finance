<?php

namespace App\Telegram\Callbacks;

use App\Models\TelegramGroupOperator;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Log;

class SetGroupOperator extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 格式不对
        if (is_null($argument)) {
            return $this->send('格式不对，正确如下：' . PHP_EOL . '设置操作员 @usernanme');
        }

        // 用户名
        $usernames = explode(' ', $argument);
        $count = 0;
        $tips = '';
        foreach ($usernames as $index => $username) {
            // 操作员对象
            $oper = new TelegramGroupOperator();

            if (str_starts_with($username, '@')) {
                // 有用户名
                $oper->username = $username;
                // 已经存在
                $old_oper = TelegramGroupOperator::where('group_id', $this->update->getChatId())->where('username', $username)->first();
                if (!empty($old_oper)) {
                    $tips .= $username . ' 已加过，';
                    continue;
                }
            } else {
                // 没有用户名
                $user = $this->update->getMessageEntities()[$index]['user'] ?? [];
                if (!empty($user)) {
                    $oper->user_id = $user['id'];
                    $oper->first_name = $user['first_name'] ?? null;
                    $oper->last_name = $user['last_name'] ?? null;

                    // 已经存在
                    $old_oper = TelegramGroupOperator::where('group_id', $this->update->getChatId())->where('user_id', $user['id'])->first();
                    if (!empty($old_oper)) {
                        $tips .= (($oper->first_name ?? '') . $oper->last_name) . ' 已加过，';
                        continue;
                    }
                }
            }

            // 保存操作员
            if ($oper->isDirty()) {
                $count++;
                $oper->group_id = $this->update->getChatId();
                $oper->setter_id = $this->update->getFromId();
                $oper->saveOrFail();
            }
        }

        // 返回结果
        return $count > 0 
                ? $this->send('设置操作人完成，共增加 ' . $count . ' 位操作人') 
                : $this->send('设置操作人完成，' . $tips . '共增加 ' . $count . ' 位操作人');
    }
}
