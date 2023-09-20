<?php

namespace App\Telegram;
use App\Telegram\Robot;

/**
 * 回调类
 */
abstract class Callback
{
    /**
     * 命令
     */
    protected $usage = 'trial';

    /**
     * 仅私人聊天可用
     */
    protected $private_only = true;

    /**
     * 构造函数
     */
    public function __construct(protected Robot $robot, protected array $update, array $arguments = [])
    {
        $this->execute($arguments);
    }

    /**
     * 执行命令
     */
    abstract public function execute(array $arguments = []) : void;
}