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
            $robot = new Robot($bot);
            $user = $robot->getMe();
            var_dump($user);
            // 输出信息
            $this->info($user['username']);
            $this->info($user['first_name'] . ' ' . ($user['last_name'] ?? ''));
            $this->newLine();
            // 获取聊天信息
            $chat = $robot->getChat([
                'chat_id'   =>  $user['id'],
            ]);
            var_dump($chat);
            // 获取头像
            if (!empty($chat['photo'])) {
                $photo = $robot->getFile([
                    'file_id'   =>  $chat['photo']['big_file_id']
                ]);
                var_dump($photo);
                var_dump('https://127.0.0.1:8081/file/bot' . $robot->token . '/' . $photo['file_path']);
            }
            
            // 获取昵称
            $nickname = $robot->getMyName();
            var_dump($nickname);
            // 获取描述
            $Desc = $robot->getMyDescription();
            var_dump($Desc);
            // 获取描述
            $sDesc = $robot->getMyShortDescription();
            var_dump($sDesc);
            // 获取命令
            $commands = $robot->getMyCommands();
            var_dump($commands);
            // 获取HOOK
            $hook = $robot->getWebhookInfo();
            var_dump($hook);
        }
    }
}
