<?php

namespace App\Telegram;
use Illuminate\Support\Facades\Log;

/**
 * 回调类
 */
abstract class Callback
{
    /**
     * 构造函数
     */
    public function __construct(public Robot $robot, public Update $update)
    { }

    /**
     * 程序处理
     */
    public function handle(string $argument = null) : mixed
    {
        // 数据验证
        if (!$this->validate($argument)) {
            return false;
        }

        // 执行指令
        return $this->execute($argument);
    }

    /**
     * 验证数据
     */
    public function validate(string $argument = null) : bool
    {
        // 验证成功
        return true;
    }

    /**
     * 执行命令
     */
    abstract public function execute(string $argument = null) : mixed;

    /**
     * 未知情况
     */
    public function send(string $content = null, array $option = []) : mixed
    {
        if ($content) {
            // 准备内容
            $context = array_merge([
                'chat_id'       =>  $this->update->getChatId(),
                'text'          =>  $content,
            ], $option);

            // 发送消息
            return $this->robot->sendMessage($context);
        }
        
        // 返回结果
        return 'none';
    }
}
