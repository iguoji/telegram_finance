<?php

namespace App\Telegram\Callbacks;

use App\Models\Order;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PlaceOrder extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 调整参数
        $price_id = (int)($argument ?? 0);

        // 查询定价
        $prices = Cache::get('telegram:robot:' . $this->robot->robot->id . ':prices');
        if (empty($prices)) {
            Log::debug(__CLASS__, [1, $argument]);
            return $this->send('很抱歉、暂未开放充值通道！');
        }

        // 找到定价
        $price = null;
        foreach ($prices as $key => $model) {
            if ($model->id == $price_id) {
                $price = $model;
            }
        }
        if (empty($price)) {
            Log::debug(__CLASS__, [2, $argument]);
            return $this->send('很抱歉、错误的充值方式！');
        }

        // 查询用户
        $user = Cache::get('telegram:user:' . $this->update->getFromId());
        if (empty($user)) {
            Log::debug(__CLASS__, [3, $argument]);
            return $this->send('很抱歉、账号异常请稍后重试！');
        }

        do {
            // 订单金额
            $money = $price->number + (rand(0, 999) / 1000);
        } while (empty(Order::where('money', $money)->where('status', '<>', 1)->first()));

        // 生成订单
        $order = new Order();
        $order->id = $order->generateId();
        $order->status = 2;
        $order->user_id = $this->update->getFromId();
        $order->robot_id = $this->robot->robot->id;
        $order->price_id = $price->id;
        $order->is_fill = 0;
        $order->money = $money;
        $order->user_trial_at = $user->trial_at;
        $order->saveOrFail();

        // 准备内容
        $context = '订单已创建！请在2小时内' . PHP_EOL .
                    '支付 ' . $order->money . ' USDT' . PHP_EOL .
                    $price->type . '钱包地址：' . ($price->address ?? '') . PHP_EOL .
                    PHP_EOL .
                    '- 注：地址为' . (substr(($price->address ?? ''), -5)) . '结尾' . PHP_EOL .
                    '- 请务必按指定金额和小数转账，否则无法自动化延期。' . PHP_EOL .
                    '- 充值成功后，3分钟后再次查看时间。' . PHP_EOL .
                    '- 如充值有问题，请联系卖家 (10:00-0:00)';

        // 发送消息
        return $this->robot->editMessageText([
            'chat_id'       =>  $this->update->getChatId(),
            'message_id'    =>  $this->update->getMessageId(),
            'text'          =>  $context,
        ]);
    }
}
