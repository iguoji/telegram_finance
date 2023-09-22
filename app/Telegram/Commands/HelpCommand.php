<?php

namespace App\Telegram\Commands;

use App\Telegram\Command;
use Illuminate\Support\Facades\Log;

class HelpCommand extends Command
{
    /**
     * 名称
     */
    public $name = 'help';

    /**
     * 描述
     */
    public $description = '帮助文档，详细介绍了怎么使用该机器人！';

    /**
     * 命令
     */
    public $usage = '/help';

    /**
     * 执行命令
     */
    public function execute(string $argument = null) : mixed
    {
        // 准备内容
        $context = [
            'chat_id'       =>  $this->getUpdate()->getChatId(),
            'text'          =>  '帮助文档' . PHP_EOL .
                                PHP_EOL . 
                                '①邀请机器人进群。群右上角--Add member-输入@zidongjizhang_bot' . PHP_EOL .
                                '②输入”设置费率X.X%“' . PHP_EOL .
                                '③输入”设置美元汇率6.5”' . PHP_EOL .
                                '④输入”开始”，每天必须先输入此命令，机器人才会开始记录。默认是每天4点至第二天4点' . PHP_EOL .
                                PHP_EOL . 
                                PHP_EOL . 
                                '⑤其它命令：' . PHP_EOL .
                                '+100' . PHP_EOL .
                                '下发100' . PHP_EOL .
                                '下发100u' . PHP_EOL .
                                '+100/6.5' . PHP_EOL .
                                '显示账单   显示最近5条数据。' . PHP_EOL .
                                '显示完整账单  出现链接，点击链接显示今天/昨天所有入款数据。' . PHP_EOL .
                                PHP_EOL . 
                                '设置操作人 @xxxxx @xxxx  设置群成员使用。先打空格再打@，会弹出选择更方便。注：再次设置的话就是新增。' . PHP_EOL .
                                '-或群内回复某人消息发：设置为操作人' . PHP_EOL .
                                '显示操作人' . PHP_EOL .
                                '删除操作人 @xxxxx 先输入“删除操作人” 然后空格，再打@，就出来了选择，这样更方便' . PHP_EOL .
                                PHP_EOL . 
                                '设置美元汇率6.5  如需显示美元，可设置这个，汇率可改变，隐藏的话再次设置为0。' . PHP_EOL .
                                '设置比索汇率7.2  如需显示比索，同上。' . PHP_EOL .
                                PHP_EOL . 
                                '设置为计数模式  只显示入款简洁模式' . PHP_EOL .
                                '设置显示模式2  账单显示3条' . PHP_EOL .
                                '设置显示模式3  账单显示1条' . PHP_EOL .
                                '设置显示模式4  只显示总入款' . PHP_EOL .
                                '设置为原始模式' . PHP_EOL .
                                '清理今天数据  慎用，必须由权限人发送命令' . PHP_EOL .
                                '结束记录' . PHP_EOL .
                                PHP_EOL . 
                                '⑥如果输入错误，可以用 入款-XXX  或 下发-XXX，来修正' . PHP_EOL .
                                PHP_EOL . 
                                '⑦【USDT独立功能】命令：' . PHP_EOL .
                                'lk  列出欧易实时价银行卡价格' . PHP_EOL .
                                'lz  列出支付宝价格' . PHP_EOL .
                                'lw  列出微信价格' . PHP_EOL .
                                PHP_EOL . 
                                'k100   实时卡价计算100元换算usdt' . PHP_EOL .
                                'z100   实时支价计算100元换算usdt' . PHP_EOL .
                                'w100   实时微价计算100元换算usdt' . PHP_EOL .
                                PHP_EOL . 
                                '/set 5   设置费率5%' . PHP_EOL .
                                '/gd 6.8   汇率固定' . PHP_EOL .
                                '/usdt   设置币价功能',
            // 'parse_mode'    =>  'Markdown',
        ];

        // 发送消息
        return $this->robot->sendMessage($context);
    }
}
