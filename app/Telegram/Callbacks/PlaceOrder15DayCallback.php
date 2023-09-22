<?php

namespace App\Telegram\Callbacks;

use App\Models\User;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PlaceOrder15DayCallback extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        Log::debug('PlaceOrder15DayCallback');
        
        // 准备内容
        $context = [
            'chat_id'       =>  $this->getUpdate()->getChatId(),
            'message_id'    =>  $this->getUpdate()->getMessageId(),
            'text'          =>  '订单已创建！请在2小时内' . PHP_EOL .
                                '支付 ' . 0 . ' USDT' . PHP_EOL .
                                'TRC-20地址：' . $this->getRobot()->trc20 . PHP_EOL .
                                PHP_EOL .
                                '- 注：地址为' . (substr($this->getRobot()->trc20, -5)) . '结尾' . PHP_EOL .
                                '- 请务必按指定金额和小数转账，否则无法自动化延期。' . PHP_EOL .
                                '- 充值成功后，3分钟后再次查看时间。' . PHP_EOL .
                                '- 如充值有问题，请联系卖家 (10:00-0:00)',
        ];

        // 发送消息
        return $this->robot->editMessageText($context);
    }
}
