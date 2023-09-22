<?php

namespace App\Console\Commands;

use App\Telegram\Robot;
use Illuminate\Console\Command;

class TelegramUnCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:uncommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unset Command description';

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
            $res = (new Robot($bot))->deleteMyCommands([
                // 'scope'     =>  json_encode([
                //     'type'  =>  'all_private_chats',
                // ]),
            ]);

            // 输出信息
            $this->info($bot);
            $this->info($res);
            $this->newLine();
        }
    }
}
