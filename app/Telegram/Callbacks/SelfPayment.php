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
        // 查询定价
        $prices = Cache::get('telegram:robot:' . $this->robot->robot->id . ':prices');
        if (empty($prices)) {
            return $this->send('很抱歉、暂未开放充值通道！');
        }

        // 整理键盘
        $inline_keyboard = [];
        foreach ($prices as $key => $price) {
            $inline_keyboard[] = [
                ['text' => $price->label, 'callback_data' => 'PlaceOrder ' . $price->id],
            ];
        }

        // 返回结果
        return $this->send('自助续费暂只支持USDT的ERC20通道', [
            'reply_markup'  =>  [
                'inline_keyboard'    =>  $inline_keyboard,
            ],
        ]);
    }
}
