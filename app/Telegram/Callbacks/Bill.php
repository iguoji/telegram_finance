<?php

namespace App\Telegram\Callbacks;

use App\Models\BillDetail;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Bill extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 今日日期
        $date = date('Y-m-d');
        // 缓存Key
        $billKey = 'bill:' . $this->robot->username . ':' . $this->update->getChatId() . ':' . $date;
        $inDetailsKey = $billKey . ':details:in';
        $outDetailsKey = $billKey . ':details:out';

        // 今日账单
        $bill = Cache::get($billKey, []);

        // 收款记录
        $inList = Cache::get($inDetailsKey, []);
        $inList = array_slice($inList, -5);

        // 出款记录
        $outList = Cache::get($outDetailsKey, []);
        $outList = array_slice($outList, -5);

        // 组织文本
        $content = '';

        // 文本 - 入款
        $content .= '入款(' . ($bill['in_count'] ?? 0) . '笔)' . PHP_EOL;
        foreach ($inList as $item) {
            $content .= '`' . $item[0] . '`     *' . $item[1] . '*' . PHP_EOL;
        }

        // 文本 - 下发
        $content .= PHP_EOL . '下发(' . ($bill['out_count'] ?? 0) . '笔)' . PHP_EOL;
        foreach ($outList as $item) {
            $content .= '`' . $item[0] . '`     *' . $item[1] . '*' . PHP_EOL;
        }

        // 文本 - 汇总
        $content .= PHP_EOL . '总入款：' . ($bill['in'] ?? 0) . PHP_EOL;
        $content .= '费率：' . ($bill['rate'] ?? 0) . '%' . PHP_EOL;
        $content .= '应下发：' . (float) number_format($out = (($bill['in'] ?? 0) * (($bill['rate'] ?? 0) / 100)), 4, '.', '') . PHP_EOL;
        $content .= '总下发：' . ($bill['out'] ?? 0) . PHP_EOL;
        $content .= '余：' . ($out - ($bill['out'] ?? 0)). PHP_EOL;

        // 返回结果
        return $this->send(
            $content,
            [
                'parse_mode'    =>  'Markdown',
                'reply_markup'  =>  [
                    'inline_keyboard'   =>  [
                        [
                            ['text' => '点击跳转完整账单', 'url' => 'https://nl.zidongjizhang.com/group/' . $this->update->getChatId() . '/bill']
                        ]
                    ]
                ]
            ]
        );
    }
}
