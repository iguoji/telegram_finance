<?php

namespace App\Console\Commands;

use App\Telegram\Robot;
use Illuminate\Console\Command;

class TelegramGetMe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:getme';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'show bot info';

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
            $user = (new Robot($bot))->getMe();
            // 输出信息
            $this->info($user['username']);
            $this->info($user['first_name']);
            $this->newLine();
        }
    }
}
