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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PullTrc20 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::debug(__CLASS__, ['start']);

        // 从订单中找出需要检查的钱包地址
        $orders = Order::where('status', 2)->orderBy('id')->get();
        $addresses = [];
        foreach ($orders as $order) {
            if ($order->price->type == 'TRC20' && !in_array($order->price->address, $addresses)) {
                $addresses[] = $order->price->address;
            }
        }

        // 循环钱包地址
        foreach ($addresses as $address) {
            // 去检索数据
            $this->tronGrid($address);
        }

        Log::debug(__CLASS__, ['end']);
    }

    /**
     * https://www.trongrid.io/documents
     */
    public function tronGrid(string $address, string $url = '')
    {
        // 开始时间
        $timestamp = Cache::get('trc20:' . $address . ':timestamp', '0');

        // 执行请求
        $res = Http::get($url ? $url : ($url = 'https://api.trongrid.io/v1/accounts/' . $address . '/transactions/trc20?only_confirmed=true&only_to=true&limit=20&min_timestamp=' . $timestamp));

        Log::debug('pull trc20 request start', [$url, $res->status()]);

        if ($res->ok()) {
            // 获取数据
            $obj = $res->json();
            if (empty($obj)) {
                // 返回空数据
                Log::error('pull trc20 request empty', [$url, $res->status(), $res->body()]);
            } else {
                // 执行成功
                if ($obj['success']) {
                    // 循环数据
                    foreach ($obj['data'] as $key => $item) {
                        // 缓存时间
                        if (empty($fingerprint) && empty($key)) {
                            Cache::put('trc20:' . $address . ':timestamp', $item['block_timestamp']);
                        }
                        // 交易对象
                        $log = TronTransaction::firstOrCreate([
                            'id'        =>  $item['transaction_id'],
                            'from'      =>  $item['from'],
                            'to'        =>  $item['to'],
                            'number'    =>  $item['value'] / 1000000,
                            'timestamp' =>  $item['block_timestamp'],
                        ]);
                        Log::debug('pull trc20 sucess', [$item['transaction_id'], $item['value'] / 1000000]);
                    }
                    // 存在下一页
                    if (!empty($obj['meta']['fingerprint']) && !empty($obj['meta']['links']['next'])) {
                        $this->tronGrid($address, $obj['meta']['links']['next']);
                    }
                } else {
                    // 执行失败
                    Log::error('pull trc20 request fail', [$url, $res->status(), $obj]);
                }
            }

        } else {
            // 请求失败
            Log::error('pull trc20 request error', [$url, $res->status(), $res->body()]);
        }
    }

    /**
     *
     */
    public function tronScan(string $address)
    {
        // // 循环Keys，依次使用
        // foreach ($tronKeys as $tronKey) {
        //     // 执行请求
        //     $res = Http::timeout(3)->retry(3, 1000)
        //                 ->withHeader('TRON-PRO-API-KEY', $tronKey)
        //                 ->get($url = 'https://apilist.tronscanapi.com/api/token_trc20/transfers?limit=2&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&start_timestamp=' . $startTime . '&confirm=&toAddress=' . $address . '&filterTokenValue=1');

        //     // 请求成功
        //     if ($res->ok()) {
        //         // 得到数据
        //         $data = $res->json();
        //         if (!empty($data['token_transfers'])) {
        //             // 整理数据
        //             $transactions = [];
        //             $time = null;
        //             foreach ($data['token_transfers'] as $item) {
        //                 $item['id'] = $item['transaction_id'];
        //                 if ($item['block_ts'] > $time) {
        //                     $time = $item['block_ts'];
        //                 }
        //                 unset($item['transaction_id'], $item['tokenInfo'], $item['tokenInfo'], $item['from_address_tag'], $item['to_address_tag']);
        //                 $transactions[] = $item;
        //             }
        //             // 缓存最新时间
        //             Cache::put('trc20:' . $address . ':block_ts', $time ?: '');
        //             // 新增数据
        //             TronTransaction::upsert($transactions, 'id');
        //         }

        //         // 结束该钱包地址的查询
        //         break;
        //     }

        //     // 记录查询错误
        //     Log::error('pull trc20', [$url, $address, $tronKey, $startTime, $res->status(), $res->body()]);
        // }
    }
}
