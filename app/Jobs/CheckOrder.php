<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\TronTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 查询订单
        $orders = Order::where('status', 2)->orderBy('created_at')->limit(50)->get();
        // 超时时间
        $timeout = config('telegram.order.timeout', 3600);
        // 循环订单
        foreach ($orders as $order) {
            // 已经超时
            if (strtotime($order->created_at) + $timeout < time()) {
                // 修改订单
                $order->changeStatus(0);
            } else {
                // 按金额查询
                $quant = (string)($order->money * 1000000);
                $transaction = TronTransaction::where('quant', $quant)
                                ->where('created_at', '>=', $order->created_at)
                                ->where('status', 0)
                                ->where('confirmed', 1)
                                ->where('contractRet', 'SUCCESS')
                                ->where('finalResult', 'SUCCESS')
                                ->where('contract_type', 'trc20')
                                ->where('contract_address', 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t')
                                ->whereNotExists(function($query){
                                    $query->selectRaw(1)->from('orders')->whereColumn('orders.hash', 'tron_transactions.id');
                                })->first();
                // 存在交易
                if (!empty($transaction)) {
                    // 修改状态
                    $order->changeStatus(1, $transaction->id);
                }
            }
        }
    }
}
