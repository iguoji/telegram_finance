<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Longman\TelegramBot\Telegram;

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
        $config = config('telegram.bot');
        // 实例对象
        $telegram = new Telegram($config['api_token'], $config['username']);
        // HOOK地址
        $hook = route('telegram.hook');
        // 设置HOOK
        $result = $telegram->setWebhook($hook);
        if ($result->isOk()) {
            $this->info($hook);
            $this->info($result->getDescription());
        }
    }
}
