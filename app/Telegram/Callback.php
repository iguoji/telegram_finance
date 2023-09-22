<?php

namespace App\Telegram;
use App\Telegram\Robot;

/**
 * 回调类
 */
abstract class Callback
{
    /**
     * 具体命令
     * 如果是指令，需要以/开头
     */
    public $usage = 'trial';

    /**
     * 参数数量
     */
    public $argc = 0;

    /**
     * 构造函数
     */
    public function __construct(public Robot $robot)
    {}

    /**
     * 执行命令
     */
    abstract public function execute(string $text = '', array $user = [], array $chat = [], array $message = []) : void;
}
