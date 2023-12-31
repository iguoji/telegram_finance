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
            $res = (new Robot($bot))->setWebhook([
                'url'           =>  ($url = route('telegram.hook.' . $bot)),
                'secret_token'  =>  md5($config['token']),
            ]);
            // 输出信息
            $this->info($bot);
            $this->info($url);
            $this->info($res);
            $this->newLine();
        }
    }
}
