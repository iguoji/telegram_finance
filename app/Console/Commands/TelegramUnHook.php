<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Longman\TelegramBot\Telegram;

class TelegramUnHook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:unhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'unset webhook';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 获取配置
        $config = config('telegram.bot');
        // 实例对象
        $telegram = new Telegram($config['api_token'], $config['username']);
        // 设置HOOK
        $result = $telegram->deleteWebhook();
        if ($result->isOk()) {
            $this->info($result->getDescription());
        }
    }
}
