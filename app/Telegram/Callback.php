<?php

namespace App\Telegram;
use Illuminate\Support\Facades\Log;

/**
 * 回调类
 */
abstract class Callback
{
    /**
     * 机器人对象
     */
    protected Robot $robot;

    /**
     * 更新对象
     */
    protected Update $update;

    /**
     * 配置信息
     */
    protected array $config;

    /**
     * 构造函数
     */
    public function __construct(Robot $robot = null, Update $update = null, array $config = [])
    {
        Log::debug('callback __construct', [static::class]);
        if ($robot) {
            $this->robot = $robot;
        }
        if ($update) {
            $this->update = $update;
        }
        if ($config) {
            $this->config = $config;
        }
    }

    /**
     * 设置机器人
     */
    public function setRobot(Robot $robot) : static
    {
        $this->robot = $robot;
        return $this;
    }

    /**
     * 获取机器人
     */
    public function getRobot() : Robot
    {
        return $this->robot;
    }

    /**
     * 设置更新
     */
    public function setUpdate(Update $update) : static
    {
        $this->update = $update;
        return $this;
    }

    /**
     * 获取更新
     */
    public function getUpdate() : Update
    {
        return $this->update;
    }

    /**
     * 设置配置
     */
    public function setConfig(array $config) : static
    {
        $this->config = $config;
        return $this;
    }

    /**
     * 获取配置
     */
    public function getConfig(string $key = null) : mixed
    {
        return is_null($key) ? $this->config : ($this->config[$key] ?? null);
    }

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
    public function unknow(string $content = null, array $option = []) : mixed
    {
        if ($content) {
            // 准备内容
            $context = array_merge([
                'chat_id'       =>  $this->getUpdate()->getChatId(),
                'text'          =>  $content,
            ], $option);

            // 发送消息
            return $this->robot->sendMessage($context);
        }
        
        // 返回结果
        return 'unknow';
    }
}
