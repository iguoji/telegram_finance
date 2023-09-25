<?php

namespace App\Telegram\Callbacks;

use App\Telegram\Callback;
use Illuminate\Support\Facades\Cache;

class SelfPayment extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        return $this->send('自助续费暂只支持USDT的erc或trc通道', [
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
        ]);
    }
}
