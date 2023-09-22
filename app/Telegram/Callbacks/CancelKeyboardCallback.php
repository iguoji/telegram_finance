<?php

namespace App\Telegram\Callbacks;

use App\Telegram\Callback;

class CancelKeyboardCallback extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 准备内容
        $context = [
            'chat_id'               =>  $this->getUpdate()->getChatId(),
            'text'                  =>  '取消成功!',
            'reply_to_message_id'   =>  $this->getUpdate()->getMessageId(),
            'reply_markup'          =>  [
                'remove_keyboard'   =>  true,
                'selective'         =>  true,
            ],
        ];

        // 发送消息
        return $this->robot->sendMessage($context);
    }
}
