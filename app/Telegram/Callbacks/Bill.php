<?php

namespace App\Telegram\Callbacks;

use App\Models\BillDetail;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Log;

class Bill extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 收款
        $inList = BillDetail::where('type', 1)->where('group_id', $this->update->getChatId())->limit(5)->orderByDesc('id')->get();
        // 出款
        $outList = BillDetail::where('type', 2)->where('group_id', $this->update->getChatId())->limit(5)->orderByDesc('id')->get();

        // 文本
        $content = '';
        $content .= '入款(笔)' . PHP_EOL;
        foreach ($inList as $item) {
            $content .= '*' . mb_substr($item->created_at, 11) . '* *' . (float) $item->money . '*' . PHP_EOL;
        }
        $content .= PHP_EOL . '下发(笔)';
        foreach ($outList as $item) {
            $content .= '*' . mb_substr($item->created_at, 11) . '* ' . (float) $item->money . PHP_EOL;
        }

        // 返回结果
        return $this->send(
            $content, 
            [
                'parse_mode'    =>  'Markdown',
            ]
        );
    }
}
