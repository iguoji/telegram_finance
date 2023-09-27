<?php

namespace App\Telegram\Callbacks;

use App\Models\TelegramGroupOperator;
use App\Telegram\Callback;
use Illuminate\Support\Facades\Log;

class ShowGroupOperator extends Callback
{
    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 查询操作员
        $tips = '';
        $opers = TelegramGroupOperator::where('group_id', $this->update->getChatId())->get();
        foreach ($opers as $oper) {
            $tips .= (empty($tips) ? '' : '，');
            if (!empty($oper['username'])) {
                $tips .= $oper['username'];
            } else {
                $tips .= ($oper->first_name ?? '') . ($oper->last_name ?? '');
            }
        }

        // 返回结果
        return $this->send('当前操作人：' . $tips);
    }
}
