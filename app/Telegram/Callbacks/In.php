<?php

namespace App\Telegram\Callbacks;

use App\Jobs\PushBill;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class In extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 参数为数字
        if (is_numeric($argument) && is_numeric($argument[0])) {
            // 当前数字
            $number = (float) $argument;
            if (str_starts_with($this->update->getText(), '-')) {
                $number = -$number;
            }
            // 群组id
            $group_id = $this->update->getChatId();
            // 来源用户
            $from = $this->update->getFrom();
            // 今日日期
            $date = date('Y-m-d');
            // 缓存Key
            $billKey = 'bill:' . $this->robot->username . ':' . $group_id . ':' . $date;
            $detailsKey = $billKey . ':details:in';
            // 缓存有效期
            $ttl = 86500;

            // 今日账单
            $bill = Cache::remember($billKey, $ttl, function() {
                return [
                    'rate'      =>  100,
                    'in'        =>  0,
                    'in_count'  =>  0,
                    'out'       =>  0,
                    'out_count' =>  0,
                ];
            });
            $bill['in'] += $number;
            $bill['in_count']++;
            Cache::put($billKey, $bill, $ttl);

            // 流水记录
            $details = Cache::remember($detailsKey, $ttl, function(){
                return [];
            });
            $details[] = [date('H:i:s'), $number];
            Cache::put($detailsKey, $details, $ttl);

            // 保存到数据库
            PushBill::dispatch($group_id, $date, 1, $number, $from);

            // 显示账单
            (new \App\Telegram\Callbacks\Bill($this->robot, $this->update))->execute();
        }

        // 返回结果
        return 'success';
    }
}
