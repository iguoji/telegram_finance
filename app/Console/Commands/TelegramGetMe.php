<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Entities\User;

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
        $config = config('telegram.bot');
        // 实例对象
        new Telegram($config['api_token'], $config['username']);
        // 获取数据
        $res = Request::getMe();
        if ($res->isOk()) {
            // 用户信息
            $user = $res->getResult();
            // 输出信息
            $this->info($user->first_name);
            $this->info($user->username);
        }
    }
}
