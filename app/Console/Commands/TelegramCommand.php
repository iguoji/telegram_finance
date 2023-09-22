<?php

namespace App\Console\Commands;

use App\Telegram\Robot;
use Illuminate\Console\Command;

class TelegramCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

            // 输出信息
            $this->info($bot);
            $this->newLine();

            // 最终指令列表
            $commands = [];
            // 循环指令类列表
            $commandClasses = $config['commands'] ?? [];
            foreach ($commandClasses as $commandClass) {
                // 指令对象
                $command = new $commandClass($robot);
                // 需要注册
                if ($command->is_registered) {
                    // 保存指令
                    $commands[] = [
                        'command'       =>  $command->name,
                        'description'   =>  $command->description,
                    ];
                    // 输出信息
                    $this->info($command->name . ': ' . $command->description);
                }
            }

            // 设置命令
            $res = $robot->setMyCommands([
                'commands'      =>      json_encode($commands),
            ]);
            // 输出信息
            $this->info($res);

            // 输出信息
            $this->newLine();
        }
    }
}
