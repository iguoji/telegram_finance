<?php

namespace App\Telegram\Callbacks;

use App\Models\User;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Cache;

class SelfPaymentCallback extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $this->getUpdate()->getChatId(),
            'text'          =>  '自助续费暂只支持USDT的erc或trc通道',
            'reply_markup'  =>  [
                'inline_keyboard'   =>  [
                    [
                        ['text' => '15天', 'callback_data' => 'PlaceOrder15Day'],
                    ],
                    [
                        ['text' => '一个月', 'callback_data' => 'PlaceOrder1Month'],
                    ],
                    [
                        ['text' => '三个月(9折)', 'callback_data' => 'PlaceOrder3Month'],
                    ],
                ],
            ],
        ];

        // 发送消息
        return $this->robot->sendMessage($context);
    }
}
