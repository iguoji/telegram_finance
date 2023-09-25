<?php

namespace App\Telegram\Callbacks;

use App\Telegram\Callback;

class CancelKeyboard extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        return $this->send(
            '取消成功!', 
            [
                'reply_to_message_id'   =>  $this->update->getMessageId(),
                'reply_markup'          =>  [
                    'remove_keyboard'   =>  true,
                    'selective'         =>  true,
                ],
            ]
        );
    }
}
