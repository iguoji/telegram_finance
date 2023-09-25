<?php

namespace App\Telegram\Callbacks;

use App\Models\TelegramUser;
use App\Telegram\Callback;
use App\Telegram\Chat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Start extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 发送消息
        $this->send('我是自动记账机器人!' . PHP_EOL . '详细信息可以查看[官网介绍](' . config('app.url') . ')!', [
            'parse_mode'    =>  'Markdown',
            'reply_markup'  =>  [
                'inline_keyboard'       =>  [
                    [
                        ['text' => '点击试用3小时', 'callback_data' => 'trial'],
                    ],
                    [
                        ['text' => '拉我进群', 'url' => 'https://t.me/' . $this->robot->username . '?startgroup=botstart'],
                    ],
                ],
            ],
        ]);

        // 如果是私聊
        if ($this->update->getChatType() == Chat::TYPE_PRIVATE) {
            // 发送空消息、设置键盘
            $this->send('(*^_^*)', [
                'reply_markup'  =>  [
                    'keyboard'      =>  $this->robot->robot->private_keyboard,
                ],
            ]);
        }

        // 注册用户
        if (empty(Cache::get('telegram:user:' . $this->update->getFromId()))) {
            // 调整参数
            $from = $this->update->getFrom();
            // 创建保存
            $user = TelegramUser::updateOrCreate($from);
            // 更新缓存
            Cache::forever('telegram:user:' . $user->id, $user);
        }

        // 返回结果
        return 'success';
    }
}
