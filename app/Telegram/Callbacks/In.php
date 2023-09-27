<?php

namespace App\Telegram\Callbacks;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Telegram\Callback;
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
        if (is_numeric($argument)) {
            // 当前数字
            $number = (float) $argument;
            $update= $this->update;
            DB::transaction(function() use($number, $update){
                // 今日账单
                $bill = Bill::where('date', $date = date('Y-m-d'))->where('group_id', $update->getChatId())->first();
                if (empty($bill)) {
                    $bill = new Bill();
                    $bill->group_id = $update->getChatId();
                    $bill->date = $date;
                    $bill->rate = 100;
                    $bill->in = $number;
                    $bill->out = 0;
                } else {
                    $bill->in += $number;
                }
                $bill->saveOrFail();

                // 流水明细
                $billDetail = new BillDetail();
                $billDetail->bill_id = $bill->id;
                $billDetail->group_id = $bill->group_id;
                $billDetail->type = 1;
                $user = $update->getFrom();
                $billDetail->user_id = $user['id'];
                $billDetail->username = $user['username'];
                $billDetail->first_name = $user['first_name'];
                $billDetail->last_name = $user['last_name'] ?? null;
                $billDetail->money = $number;
                $billDetail->saveOrFail();
            });
            
            // 显示账单
            (new \App\Telegram\Callbacks\Bill($this->robot, $this->update))->execute();
        }

        // 返回结果
        return 'success';
    }
}
