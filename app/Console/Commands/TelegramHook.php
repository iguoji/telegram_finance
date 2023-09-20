<?php

namespace App\Console\Commands;

use App\Telegram\Robot;
use Illuminate\Console\Command;

class TelegramHook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:hook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set webhook';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 获取配置
        $configs = config('telegram.bots');
        // 循环配置
        foreach ($configs as $bot => $config) {
            // 机器人实例
            $robot = new Robot($bot);
            // 设置WebHook
            $res = $robot->setWebhook();
            // 输出信息
            $this->info($bot);
            $this->info($robot->webhook);
            $this->info($res);
            $this->newLine();
        }
    }
}
